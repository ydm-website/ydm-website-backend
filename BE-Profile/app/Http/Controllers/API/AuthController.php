<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function getUser() {
        $currentUser = auth()->user();

        $user = User::with(['role'])->where('id', $currentUser->id)->first();

        return response()->json([
            "message" => "Get user was successful",
            "user" => $user,
        ]);
    }

    public function login(Request $request) {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                "message" => "User Invalid"
            ], 401);
        }

        $user = User::with('Role')->where('email', $request['email'])->first();
        $token = JWTAuth::fromUser($user);
        return response()->json([
            "user" => $user,
            "token" => $token,
        ]);
    }

    public function logout() {
        auth()->logout();

        return response()->json([
            "message" => "Logout Success"
        ]);
    }
}
