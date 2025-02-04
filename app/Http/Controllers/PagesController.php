<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function getIndex() {
        return view('pages/home');
    }
    public function getLogin() {
        return view('pages/login');
    }
    public function getRegister() {
        return view('pages/register');
    }
    public function getEmailVerify() {
        return view('pages/verify-notice');
    }
    public function getProfile() {
        return view('pages/profile')->with('user', Auth::user());
    }
    public function getChangePassword() {
        return view('pages/change-password');
    }
    public function getForgotPassword(Request $request) {
        return view('pages/password-reset', ['token' => $request->token], ['email' => request('email')]);
    }
}
