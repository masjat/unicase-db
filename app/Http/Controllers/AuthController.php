<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
{
    try {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'user'   => $user,
            'token'  => $token,
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Validasi gagal',
            'errors'  => $e->errors(),
        ], 422);
    }
}
    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'user'   => $user,
            'token'  => $token,
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil.',
        ]);
    }
    //Update
    public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|string|email|unique:users',
        'phone_number'   => 'nullable|string|max:20',
        'gender'         => 'nullable|in:male,female',
        'date_of_birth'  => 'nullable|date',
        'image'          => 'nullable|url',
    ]);

    $user->update([
        'name'           => $request->name,
        'email'          => $request->email,
        'phone_number'   => $request->phone_number,
        'gender'         => $request->gender,
        'date_of_birth'  => $request->date_of_birth,
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Profil berhasil diperbarui',
        'user'    => $user
    ]);
}

}
