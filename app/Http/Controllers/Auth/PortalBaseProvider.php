<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class PortalBaseProvider extends AbstractProvider implements ProviderInterface
{
    const PORTAL_BASE_URL = 'https://portal.ncu.edu.tw';

    protected $scopeSeparator = ' ';

    protected $scopes = [
        'identifier',
        'chinese-name',
        'english-name',
        'gender',
        'birthday',
        'personal-id',
        'student-id',
        'faculty-records',
        'academy-records',
        'email',
        'mobile-phone',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(self::PORTAL_BASE_URL . '/oauth2/authorization', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return self::PORTAL_BASE_URL . '/oauth2/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = self::PORTAL_BASE_URL . '/apis/oauth/v1/info';

        
        \Log::info('NCU Portal User Info Request:', [
            'url' => $userUrl,
            'token' => $token,
        ]);

        $response = Http::withoutVerifying()
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])
            ->get($userUrl);

        $responseContent = $response->getBody()->getContents();
        \Log::info('NCU Portal User Info Response:', [
            'status' => $response->status(),
            'body' => $responseContent,
        ]);
        return (array) json_decode($responseContent, true);
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        // Logging the request for debugging purposes
        \Log::info('NCU Portal Access Token Request:', [
            'code' => $code,
        ]);

        $response = Http::withoutVerifying()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->withHeaders(['Accept' => 'application/json'])
            ->asForm()
            ->post($this->getTokenUrl(), $this->getTokenFields($code));

        $responseContent = $response->getBody()->getContents();
        // Logging the response for debugging purposes
        \Log::info('NCU Portal Access Token Response:', [
            'status' => $response->status(),
            'body' => $responseContent,
        ]);

        return json_decode($responseContent, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        \Log::info('Portal user data:', $user);
        return (new User)->setRaw($user)->map([
            'id' => $user['identifier'],
            'name' => $user['identifier'],
            'nickname' => $user['chineseName'],
            'email' => $user['email'],
            'email_verified' => $user['emailVerified'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }
}
