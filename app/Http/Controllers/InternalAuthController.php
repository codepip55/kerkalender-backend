<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class InternalAuthController extends Controller
{
    public function login(Request $request) {
        $code = $request->query('code');

        if ($code) {
            $req_data = $this->callback($request);
        } else {
            return $this->redirect($request);
        }

        // Array indicates an error
        if (is_array($req_data) || $req_data === null) {
            error_log('Error: ' . json_encode($req_data));
            return $req_data;
        }
        $req_data = $req_data->getData(true);

        $payload = [ 'sub' => $req_data['user']['id'] ];
        $refresh_token= $req_data['refreshToken'];

        $cookie = cookie(
            env('AUTH_COOKIE_NAME'),
            $refresh_token,
            30 * 24 * 60,
            null,
            env('AUTH_COOKIE_DOMAIN'),
            env('AUTH_COOKIE_DOMAIN') !== 'localhost',
            true,
            false,
            'None');

        $payload = JWTAuth::getPayloadFactory()->customClaims($payload)->make();
        $token = JWTAuth::encode($payload)->get();
        $expires_in = 30 * 60 * 1000; // 30 minutes

        return response()->json([
            'user' => $req_data['user'],
            'token' => $token,
            'expires_in' => $expires_in,
        ])->cookie($cookie);
    }
    // Redirect user to the provider's authentication page
    public function redirect(Request $request) {
        $state = $request->query('state');

        $query = http_build_query([
            'client_id' => env('KERKALENDER_CLIENT_ID'),
            'redirect_uri' => env('KERKALENDER_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect('/oauth/authorize?' . $query);
    }
    public function callback(Request $request) {
        $state = $request->query('state');

        $response = Http::asForm()->post(env('APP_URL').'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('KERKALENDER_CLIENT_ID'),
            'client_secret' => env('KERKALENDER_CLIENT_SECRET'),
            'redirect_uri' => env('KERKALENDER_REDIRECT_URI'),
            'code' => $request->query('code'),
        ]);

        if ($response->successful()) {
            $creds = $response->body();
        } else {
            return json_decode($response->body(), true);
        }

        $creds = json_decode($creds, true);

        return $this->validate($creds['access_token'], $creds['refresh_token']);
    }
    private function validate(string $accessToken, string $refreshToken) {
        $user_url = env('APP_URL').'/api/user/bearer';
        $user_response = Http::withToken($accessToken)->acceptJson()->get($user_url);

        // Handle error
        if (!$user_response->successful()) {
            return $user_response->json();
        }

        $user = $user_response->json();

        return response()->json([
            'user' => $user,
            'refreshToken' => $refreshToken
        ]);
    }
    public function silentAuth() {
        $cookie_name = env('AUTH_COOKIE_NAME');
        try {
            $refresh_token = $_COOKIE[$cookie_name];
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Exchange refresh token for User details
        $response = Http::asForm()->post(env('APP_URL').'/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => env('KERKALENDER_CLIENT_ID'),
            'client_secret' => env('KERKALENDER_CLIENT_SECRET'),
            'scope' => '',
        ]);

        $creds = json_decode($response->body(), true);

        if (!($response->successful())) {
            return response()->json([
                'message' => 'Unauthorized',
                'body' => $response->body()
            ], 401);
        }

        $user = $this->validate($creds['access_token'], $creds['refresh_token']);

        // Sign token
        $payload = [ 'sub' => $user['user']['id'] ];
        $new_refresh_token= $user['refreshToken'];

        // Set new refresh token
        $cookie = cookie(
            env('AUTH_COOKIE_NAME'),
            $refresh_token,
            30 * 24 * 60,
            null,
            env('AUTH_COOKIE_DOMAIN'),
            env('AUTH_COOKIE_DOMAIN') !== 'localhost',
            true,
            false,
            'None');

        $payload = JWTAuth::getPayloadFactory()->customClaims($payload)->make();
        $token = JWTAuth::encode($payload)->get();
        $expires_in = 30 * 60 * 1000; // 30 minutes

        return response()->json([
            'user' => $user['user'],
            'token' => $token,
            'expires_in' => $expires_in,
        ])->cookie($cookie);
    }
    public function removeRefreshCookie() {
        setcookie(
            env('AUTH_COOKIE_NAME'),
            '', [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => env('AUTH_COOKIE_DOMAIN'),
                'secure' => env('AUTH_COOKIE_DOMAIN') !== 'localhost',
                'httponly' => true,
                'samesite' => 'None'
            ]);
    }
}
