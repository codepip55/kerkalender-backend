<?php

namespace App\Services;

use App\Models\User;
use League\Uri\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService {
    public function getTokens(User $user) {
        $payload = [ 'sub' => $user->id ];

        $refreshToken = $user->refreshToken;
        $user->refreshToken = null;

        $token = JWTAuth::fromUser($user);

        return [
            'token' => $token,
            'expiresIn' => JWTAuth::factory()->getTTL() * 60,
            'refreshToken' => $refreshToken,
        ];
    }
    public function refresh(string $refreshToken) {
        $response = Http::asForm()->post(env('KERKALENDER_TOKEN_URL'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => env('KERKALENDER_CLIENT_ID'),
            'client_secret' => env('KERKALENDER_CLIENT_SECRET'),
            'scope' => env('KERKALENDER_SCOPES'),
        ]);

        if ($response->failed()) {
            throw new \Exception('Unauthorized');
        }

        $creds = $response->json();

        $userResponse = Http::withToken($creds['access_token'])->get(env('KERKALENDER_USER_URL'));

        if ($userResponse->failed()) {
            throw new \Exception('Unauthorized');
        }

        $userData = $userResponse->json();

        $user = User::firstOrCreate(
            ['id' => $userData['id']],
            [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'teams' => $userData['teams'],
                'services_managed' => $userData['services_managed'],
            ]
        );

        return [
            'user' => $user,
            'token' => JWTAuth::fromUser($user),
            'expiresIn' => 30 * 60 * 1000,
            'newRefreshToken' => $creds['refresh_token'],
        ]
    }
}
