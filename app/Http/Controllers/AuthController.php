<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;
use function Laravel\Prompts\error;

class AuthController extends Controller
{
    private $internalauthcontroller;

    public function __construct(InternalAuthController $internalauthcontroller) {
        $this->internalauthcontroller = $internalauthcontroller;
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended();
        }

        // If authentication fails, redirect back with an error message
        // but preserve the intended URL
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'De combinatie van email en wachtwoord is onjuist.',
            ]);
    }
    public function register(Request $request) {
        $credentials = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'string'],
        ]);

        $user = new User();
        $user->name = $credentials['first_name'] . ' ' . $credentials['last_name'];
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();

        event(new Registered($user));

        return redirect()->route('verification.notice');
    }
    public function verifyEmail(EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/');
    }
    public function sendEmailVerificationNotification(Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }
    public function changePassword(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $credentials
        );

        $status === Password::RESET_LINK_SENT
            ? back()->with('status', "We hebben je een email gestuurd om je wachtwoord te wijzigen.")
            : back()->withErrors(['email' => "We kunnen geen gebruiker vinden met dat email adres."]);

        return back();
    }
    public function resetPassword(Request $request) {
        $credentials = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8', 'string'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(STR::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Je wachtwoord is gewijzigd!')
            : back()->withErrors(['email' => "Er is iets misgegaan bij het wijzigen van je wachtwoord."]);
    }
    public function logout(Request $request) {
        $this->removeTokens($request);
        return redirect('/');
    }
    public function logoutApi(Request $request) {
        $this->removeTokens($request);
        return response()->json(['message' => 'Successfully logged out']);
    }
    private function removeTokens(Request $request) {
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        if ($request->user()->token()) {
            $tokenRepository->revokeAccessToken($request->user()->token());
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($request->user()->token()->id);
        }

        $this->internalauthcontroller->clearRefreshToken();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
