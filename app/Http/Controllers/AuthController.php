<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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

    /** @var User $user */
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

    public function logout(Request $request)
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function getProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();
        $user->load('userDetail');

        return response()->json([
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|min:8|confirmed',
            'first_name' => 'sometimes|nullable|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:30',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gender' => 'sometimes|nullable|string|max:50',
        ]);

        $userData = array_intersect_key($validated, array_flip(['name', 'email', 'password']));
        if (array_key_exists('password', $userData)) {
            $userData['password'] = Hash::make($userData['password']);
        }

        if ($userData !== []) {
            $user->update($userData);
        }

        $detailData = array_intersect_key($validated, array_flip([
            'first_name',
            'last_name',
            'address',
            'phone_number',
            'gender',
        ]));

        if ($request->hasFile('profile_picture')) {
            $oldPicture = $user->userDetail?->profile_picture;
            $detailData['profile_picture'] = $request->file('profile_picture')
                ->store('profile-pictures', 'public');
        } else {
            $oldPicture = null;
        }

        if ($detailData !== []) {
            $user->userDetail()->updateOrCreate([], $detailData);
        }

        if ($oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }

        $user->load('userDetail');

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

}
