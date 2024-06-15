<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    public function usershow($id)
    {
        $product = User::findOrFail($id);
        return response()->json($product);
    }
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

        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
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

    public function userUpdate(Request $request, $id)
    {
        $request->validate([
            'email' => 'email',
            'phone' => 'string',
            'address' => 'string',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return response()->json(['message' => 'User updated successfully'], 200);
    }
    public function userAvatar(Request $request, $id)
    {
        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        $user = User::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('avatars'), $filename);
            $user->image = $filename;
            $user->save();
        }

        return response()->json(['message' => 'Image updated successfully'], 200);
    }

    public function userUpdateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'nullable|string|max:255',
        ]);

        $user = User::find($id);
        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User updated successfully'], 200);
    }
}
