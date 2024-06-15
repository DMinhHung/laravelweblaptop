<?php

namespace App\Http\Controllers;

use App\Models\AuthAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthControllerAdmin extends Controller
{
    public function userLogin(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        if (!Auth::attempt($loginUserData)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ]);
    }

    public function userRegister(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:4'
        ]);

        $user = AuthAdmin::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'role' => 'admin',
            'password' => Hash::make($registerUserData['password']),
        ]);

        return response()->json([
            'message' => 'User Created ',
            'user' => $user,
        ]);
    }

    public function userlogout(Request $request)
    {

        $user = $request->user();

        if ($user) {
            $token = $user->currentAccessToken();
            if ($token && $token->tokenable_id === $user->id) {
                $token->delete();
                return response()->json([
                    'message' => 'Logged out successfully'
                ]);
            }
        }
        return response()->json([
            'message' => 'Logout failed: Invalid token'
        ], 401);
    }
}
