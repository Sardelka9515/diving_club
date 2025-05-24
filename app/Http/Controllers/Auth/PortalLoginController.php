<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class PortalLoginController extends Controller
{

    public function redirectToProvider()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return Socialite::with('portal')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        \Log::info('NCU Portal Socialite Callback', $request->all());
        $user = Socialite::with('portal')->user();
        if ($request->has('error')) {
            \Log::error('NCU Portal Login Error: ' . $request->input('error_description', $request->input('error')), $request->all());
            return redirect()->route('login')->with('error', 'NCU Portal 登入失敗：' . $request->input('error_description', '授權被拒絕或發生錯誤。'));
        }

        try {
            // The user object from Socialite. $ncuUser->email, $ncuUser->name, $ncuUser->getId() (portal's ID), $ncuUser->getNickname() (portal identifier/username)
            $ncuUser = Socialite::driver('portal')->user();
        } catch (Exception $e) {
            \Log::error('NCU Portal Socialite Callback Error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('login')->with('error', '無法從NCU Portal獲取用戶資訊，請稍後再試或聯繫管理員。');
        }

        // Use NCU 'identifier' (username) as the primary unique anchor if available and stable.
        // Otherwise, email can be used if it's guaranteed to be unique and verified by NCU.
        // The NCU Portal documentation mentions 'identifier' as the account.
        // $ncuUser->getNickname() usually provides the username/identifier for Socialite.
        // $ncuUser->getEmail()
        // $ncuUser->getName() (usually maps to chineseName)

        $portalIdentifier = $ncuUser->getId(); // Assuming this provides the NCU 'identifier'
        $portalEmail = $ncuUser->getEmail();
        $portalName =  $portalIdentifier;
        \Log::info('NCU Portal User Info:', [
            'identifier' => $portalIdentifier,
            'email' => $portalEmail,
            'name' => $portalName,
            'nickname' => $ncuUser->getNickname(),
            'chineseName' => $ncuUser->user['chineseName'] ?? null,
            'englishName' => $ncuUser->user['englishName'] ?? null,
            'emailVerified' => $ncuUser->user['emailVerified'] ?? false,
        ]);

        if (empty($portalIdentifier) && empty($portalEmail)) {
            \Log::error('NCU Portal Callback: Missing identifier and email for user.', (array) $ncuUser);
            return redirect()->route('login')->with('error', 'NCU Portal 未提供足夠的用戶識別資訊。');
        }

        // Prioritize NCU identifier if available, otherwise fallback to email.
        $user = null;
        if (!empty($portalIdentifier)) {
            $user = User::where('ncu_identifier', $portalIdentifier)->first();
        }

        if (!$user && !empty($portalEmail)) {
            $user = User::where('email', $portalEmail)->first();
            // If found by email, update its ncu_identifier if empty
            if ($user && empty($user->ncu_identifier) && !empty($portalIdentifier)) {
                $user->ncu_identifier = $portalIdentifier;
            }
        }

        if ($user) {
            // Update existing user
            $user->name = $portalName ?: $user->name; // Update name if provided
            if (!empty($portalEmail))
                $user->email = $portalEmail; // Update email if provided and different
            if (empty($user->ncu_identifier) && !empty($portalIdentifier))
                $user->ncu_identifier = $portalIdentifier;
            // Set email_verified_at based on NCU Portal's emailVerified status
            $user->email_verified_at = ($ncuUser->user['emailVerified'] ?? false) ? now() : null;
            $user->save();
        } else {
            // Create new user
            if (empty($portalEmail)) {
                \Log::error('NCU Portal Callback: Email is required to create a new user but was not provided.', (array) $ncuUser);
                return redirect()->route('login')->with('error', 'NCU Portal 未提供Email，無法建立新帳戶。');
            }
            $user = User::create([
                'ncu_identifier' => $portalIdentifier, // Store NCU identifier
                'name' => $portalName ?: 'NCU User',
                'email' => $portalEmail,
                'password' => Hash::make(Str::random(24)), // Random password as local login is not used
                'email_verified_at' => ($ncuUser->user['emailVerified'] ?? false) ? now() : null, // Set based on NCU Portal's verification status
            ]);
        }


        // Assign default 'user' role if not already assigned
        if (!$user->roles()->exists()) {
            $userRole = Role::where('slug', 'user')->first();
            if ($userRole) {
                $user->roles()->attach($userRole);
            }
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
