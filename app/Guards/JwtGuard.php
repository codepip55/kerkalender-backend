<?php

namespace App\Guards;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtGuard implements Guard {
    protected $provider;
    protected $request;
    protected $user;

    public function __construct(UserProvider $provider, Request $request) {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Get the authenticated user from JWT
     */
    public function user() {
        if ($this->user) {
            return $this->user;
        }

        try {
            $token = JWTAuth::parseToken();
            $payload = JWTAuth::getPayload($token)->toArray();

            if (!isset($payload['sub'])) {
                return null;
            }

            $this->user = User::find($payload['sub']);
            return $this->user;
        } catch (JWTException $e) {
            return null; // Invalid or missing token
        }
    }
    public function check() {
        return !is_null($this->user());
    }
    public function guest() {
        return !$this->check();
    }
    public function id() {
        return optional($this->user())->id;
    }
    public function setUser($user) {
        $this->user = $user;
    }
    public function hasUser() {
        return !is_null($this->user);
    }
    public function validate(array $credentials = []) {
        return false;
    }
}
