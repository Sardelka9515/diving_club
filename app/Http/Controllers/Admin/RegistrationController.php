<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\RegistrationApproved;
use App\Notifications\RegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /**
     * 核准報名
     *
     * @param Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Registration $registration)
    {
        // 確認報名狀態是待審核
        if ($registration->status !== 'pending') {
            return back()->with('error', '只有待審核的報名才能被核准。');
        }

        // 檢查活動是否已達人數上限
        $activity = $registration->activity;
        if ($activity->max_participants > 0) {
            $approvedCount = $activity->registrations()->where('status', 'approved')->count();
            if ($approvedCount >= $activity->max_participants) {
                return back()->with('error', '活動人數已達上限，無法核准更多報名。');
            }
        }

        // 更新報名狀態
        $registration->status = 'approved';
        $registration->approved_at = now();
        $registration->approved_by = Auth::id();
        $registration->save();

        // 發送通知
        try {
            $registration->user->notify(new RegistrationApproved($registration));
        } catch (\Exception $e) {
            // 記錄通知失敗但不中斷流程
            \Log::error('發送報名核准通知失敗: ' . $e->getMessage());
        }

        return back()->with('success', '已成功核准報名');
    }

    /**
     * 刪除報名記錄
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Registration $registration)
    {
        // 記錄一些信息用於審計
        $user = $registration->user;
        $activity = $registration->activity;
        $adminUser = auth()->user();

        // 記錄日誌
        \Log::info("報名記錄被刪除", [
            'admin_id' => $adminUser->id,
            'admin_name' => $adminUser->name,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'activity_id' => $activity->id,
            'activity_title' => $activity->title,
            'registration_id' => $registration->id,
            'created_at' => $registration->created_at
        ]);

        // 刪除報名記錄
        $registration->delete();

        return back()->with('success', '已拒絕並刪除報名記錄');
    }

    // /**
    //  * 拒絕報名
    //  *
    //  * @param Registration $registration
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    // public function reject(Registration $registration)
    // {
    //     // 確認報名狀態是待審核
    //     if ($registration->status !== 'pending') {
    //         return back()->with('error', '只有待審核的報名才能被拒絕。');
    //     }

    //     // 更新報名狀態
    //     $registration->status = 'rejected';
    //     $registration->rejected_at = now();
    //     $registration->rejected_by = Auth::id();
    //     $registration->save();

    //     // 發送通知
    //     try {
    //         $registration->user->notify(new RegistrationRejected($registration));
    //     } catch (\Exception $e) {
    //         // 記錄通知失敗但不中斷流程
    //         \Log::error('發送報名拒絕通知失敗: ' . $e->getMessage());
    //     }

    //     return back()->with('success', '已拒絕此報名');
    // }

    /**
     * 批量核准報名
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:registrations,id'
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($request->registration_ids as $id) {
            $registration = Registration::find($id);
            if ($registration && $registration->status === 'pending') {
                // 更新狀態
                $registration->status = 'approved';
                $registration->approved_at = now();
                $registration->approved_by = Auth::id();
                $registration->save();

                // 可以在這裡添加通知邏輯
                // $registration->user->notify(new \App\Notifications\RegistrationApproved($registration));

                $successCount++;
            } else {
                $failCount++;
            }
        }

        $message = "已成功核准 {$successCount} 份報名";
        if ($failCount > 0) {
            $message .= "，{$failCount} 份無法處理";
        }

        return back()->with('success', $message);
    }

    /**
     * 批量刪除報名記錄
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:registrations,id'
        ]);

        $adminUser = auth()->user();
        $count = 0;

        foreach ($request->registration_ids as $id) {
            $registration = \App\Models\Registration::find($id);
            if ($registration) {
                // 記錄日誌
                \Log::info("批量刪除報名記錄", [
                    'admin_id' => $adminUser->id,
                    'admin_name' => $adminUser->name,
                    'user_id' => $registration->user->id,
                    'user_name' => $registration->user->name,
                    'activity_id' => $registration->activity->id,
                    'activity_title' => $registration->activity->title,
                    'registration_id' => $registration->id,
                    'created_at' => $registration->created_at
                ]);

                // 刪除報名記錄
                $registration->delete();
                $count++;
            }
        }

        return back()->with('success', "已拒絕並刪除 {$count} 份報名記錄");
    }

    // /**
    //  * 批量拒絕報名
    //  *
    //  * @param Request $request
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    // public function bulkReject(Request $request)
    // {
    //     $request->validate([
    //         'registration_ids' => 'required|array',
    //         'registration_ids.*' => 'exists:registrations,id'
    //     ]);

    //     $count = 0;
    //     foreach ($request->registration_ids as $id) {
    //         $registration = \App\Models\Registration::find($id);
    //         if ($registration && $registration->status === 'pending') {
    //             // 更新狀態
    //             $registration->status = 'rejected';
    //             $registration->rejected_at = now();
    //             $registration->rejected_by = Auth::id();
    //             $registration->save();

    //             // 可以在這裡添加通知邏輯
    //             // $registration->user->notify(new \App\Notifications\RegistrationRejected($registration));

    //             $count++;
    //         }
    //     }

    //     return back()->with('success', "已拒絕 {$count} 份報名");
    // }

    /**
     * 匯出報名資料
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id'
        ]);

        // 這裡實現匯出邏輯，例如使用 Laravel Excel 套件
        // 請確保安裝 Laravel Excel: composer require maatwebsite/excel

        $activity = \App\Models\Activity::findOrFail($request->activity_id);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RegistrationsExport($activity),
            $activity->title . '-報名資料.xlsx'
        );
    }

    /**
     * 取消已確認的報名
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Registration $registration)
    {
        if ($registration->status !== 'approved') {
            return back()->with('error', '只有已核准的報名才能被取消');
        }

        $registration->status = 'cancelled';
        // $registration->cancelled_at = now();
        // $registration->cancelled_by = auth()->id();
        $registration->save();

        // 可以在這裡加入通知邏輯
        // $registration->user->notify(new \App\Notifications\RegistrationCancelled($registration));

        return back()->with('success', '已成功取消該報名');
    }

    /**
     * 管理員新增報名
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_id' => 'required|exists:activities,id',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        // 檢查用戶是否已經報名此活動
        $activity = \App\Models\Activity::find($validated['activity_id']);
        $existingRegistration = $activity->registrations()
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', '此用戶已報名此活動');
        }

        // 檢查活動是否已達人數上限
        if (
            $activity->max_participants > 0 &&
            $activity->registrations()->count() >= $activity->max_participants &&
            $validated['status'] == 'approved'
        ) {
            return back()->with('error', '此活動已達報名人數上限');
        }

        // 創建新報名
        $registration = new \App\Models\Registration();
        $registration->user_id = $validated['user_id'];
        $registration->activity_id = $validated['activity_id'];
        $registration->status = $validated['status'];
        $registration->notes = $validated['notes'];

        // 如果狀態是已核准，設置核准時間和核准人
        if ($validated['status'] == 'approved') {
            $registration->approved_at = now();
            $registration->approved_by = Auth::id();
        } elseif ($validated['status'] == 'rejected') {
            $registration->rejected_at = now();
            $registration->rejected_by = Auth::id();
        }

        $registration->save();

        return redirect()->route('admin.activities.show', $activity)
            ->with('success', '已成功新增報名');
    }

    /**
     * 搜索用戶 (ajax)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers(Request $request)
    {
        $term = $request->term;

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $users = \App\Models\User::where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->take(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
