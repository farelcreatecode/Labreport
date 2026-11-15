<?php

namespace App\Http\Controllers;

use App\Services\GatewayClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    protected GatewayClient $gateway;

    public function __construct(GatewayClient $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Halaman Login Admin
     */
    public function showLogin(): View|RedirectResponse
    {
        if (session()->has('admin_token')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses Login Admin via API Gateway -> Auth Service
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $response = $this->gateway->post('/auth/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json('data');
            session([
                'admin_token' => $data['token'],
                'admin_user'  => $data['user'],
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang kembali, ' . ($data['user']['name'] ?? 'Administrator'));
        }

        $errorMessage = $response->json('message') ?? 'Email atau password yang Anda masukkan salah.';
        return back()->withInput()->with('error', $errorMessage);
    }

    /**
     * Logout Admin via API Gateway -> Auth Service
     */
    public function logout(Request $request): RedirectResponse
    {
        if (session()->has('admin_token')) {
            $this->gateway->post('/auth/logout');
            session()->forget(['admin_token', 'admin_user']);
        }

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout dari sistem.');
    }
}
