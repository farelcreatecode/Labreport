<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Admin Login
     * Validasi email & password -> pastikan role admin -> kembalikan token Sanctum
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password yang Anda masukkan salah.',
            ], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Akun Anda bukan Administrator.',
            ], 403);
        }

        // Revoke token lama agar 1 sesi aktif per login (opsional) atau buat token baru
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil. Selamat datang, ' . $user->name,
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
            ],
        ]);
    }

    /**
     * Verify Admin Token
     * Digunakan oleh Report Service / API Gateway untuk memverifikasi keabsahan token
     */
    public function verifyToken(Request $request): JsonResponse
    {
        $tokenString = $request->bearerToken() ?: $request->input('token');

        if (! $tokenString) {
            return response()->json([
                'valid'   => false,
                'message' => 'Token tidak ditemukan dalam request.',
            ], 401);
        }

        $accessToken = PersonalAccessToken::findToken($tokenString);

        if (! $accessToken || ($accessToken->expires_at && $accessToken->expires_at->isPast())) {
            return response()->json([
                'valid'   => false,
                'message' => 'Token tidak valid atau sudah kedaluwarsa.',
            ], 401);
        }

        $user = $accessToken->tokenable;

        if (! $user || ! $user->isAdmin()) {
            return response()->json([
                'valid'   => false,
                'message' => 'Pengguna tidak memiliki wewenang Administrator.',
            ], 403);
        }

        return response()->json([
            'valid' => true,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Get Current Authenticated Admin
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Token telah dicabut.',
        ]);
    }
}
