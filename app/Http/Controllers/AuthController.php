<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        'email'          => 'required|string|email|unique:users' . $user->id,
        'phone_number'   => 'nullable|string|max:20',
        'gender'         => 'nullable|in:male,female',
        'date_of_birth'  => 'nullable|date',
        'image'          => 'nullable|url',
    ]);

    $user->update($request->only([
        'name','email','phone_number','gender','date_of_birth','image'
    ]));

    return response()->json([
        'status'  => true,
        'message' => 'Profil berhasil diperbarui',
        'user'    => $user
    ]);
}

     // REQUEST OTP FORGOT PASSWORD
    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Kirim ke email (dummy / Mail::to()->send())
        // Mail::to($request->email)->send(new SendOtpResetMail($token));

        return response()->json(['message' => 'Kode OTP telah dikirim ke email.']);
    }

        // VERIFY OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'Kode OTP tidak valid atau sudah kadaluarsa.'], 422);
        }

        return response()->json(['message' => 'OTP valid.']);
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'OTP tidak valid atau sudah kadaluarsa.'], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset.']);
    }
}
