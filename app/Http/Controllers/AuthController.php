<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!$token = Auth::guard('api')->attempt($credentials)) {
        return response()->json([
            'message' => 'Invalid email or password'
        ], 401);
    }

    $user = Auth::guard('api')->user();
    if (!$user->hasVerifiedEmail()) {
        return response()->json([
            'message' => 'Email not verified. Please verify your email before logging in.'
        ], 403);
    }
    return response()->json([
        'message' => 'Login successful',
        'access_token' => $token,
        'token_type' => 'bearer',
        'user' => Auth::guard('api')->user(),
    ]);
}

}
