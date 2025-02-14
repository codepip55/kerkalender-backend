<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use League\OAuth2\Client\Provider\GenericProvider;

class KerkalenderAuthService {
    public function authenticate($request) {
        $state = $request->query('state');

        $provider = new GenericProvider([
            'clientId'                => env('KERKALENDER_CLIENT_ID'),
            'clientSecret'            => env('KERKALENDER_CLIENT_SECRET'),
            'redirectUri'             => env('KERKALENDER_REDIRECT_URI'),
            'urlAuthorize'            => env('KERKALENDER_URL_AUTHORIZE'),
            'urlAccessToken'          => env('KERKALENDER_URL_ACCESS_TOKEN'),
            'urlResourceOwnerDetails' => env('KERKALENDER_URL_RESOURCE_OWNER_DETAILS'),
            'scopes'                  => env('KERKALENDER_SCOPES'),
        ]);

        $url = $provider->getAuthorizationUrl([
            'state' => $state,
        ]);

        return redirect($url);
    }

    public function handleOAuthCallback($accessToken, $refreshToken) {
        $userURL = env('KERKALENDER_URL_RESOURCE_OWNER_DETAILS');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get($userURL);

        if ($response->failed()) {
            throw new \Exception('Failed to get user details');
        }

        $userData = $response->json()['data'];

        // Try to find existing user by ID
        try {
            $user = User::where('id', $userData['id'])->firstOrFail();

            // Update user
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->teams = $userData['teams'];
            $user->services_managed = $userData['services_managed'];
            $user->save();
        } catch (\Exception $e) {
            // Create new user if not found
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $user = new User();
                $user->id = $userData['id'];
                $user->name = $userData['name'];
                $user->email = $userData['email'];
                $user->teams = $userData['teams'];
                $user->services_managed = $userData['services_managed'];
                $user->save();
            }
        }

        return $user;
    }
}
