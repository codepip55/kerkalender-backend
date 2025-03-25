<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function findById($id) {
        $user = User::find($id);
        return response()->json($user);
    }
    /**
     * Get all users
     */
    public function findUsers() {
        $users = User::all();
        return response()->json($users);
    }
}
