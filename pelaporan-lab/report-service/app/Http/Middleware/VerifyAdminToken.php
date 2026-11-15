<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyAdminToken
{
    /**
     * Handle an incoming request.
     * Verifies the Bearer token with Auth Service.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?: $request->header('X-Admin-Token');

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Token autentikasi admin tidak ditemukan.',
            ], 401);
        }

        $authUrl = config('services.auth_service.url', env('AUTH_SERVICE_URL', 'http://127.0.0.1:8002'));

        try {
            $response = Http::timeout(5)
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$authUrl}/api/verify-token", [
                    'token' => $token,
                ]);

            if ($response->successful() && $response->json('valid') === true) {
                // Simpan data user yang diverifikasi ke dalam request attributes
                $request->attributes->set('auth_user', $response->json('user'));
                return $next($request);
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Token tidak valid atau wewenang tidak mencukupi.',
            ], $response->status() ?: 401);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Auth Service untuk verifikasi token: ' . $e->getMessage(),
            ], 503);
        }
    }
}
