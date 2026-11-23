<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    protected string $authServiceUrl;
    protected string $reportServiceUrl;
    protected string $notificationServiceUrl;

    public function __construct()
    {
        $this->authServiceUrl = rtrim(env('AUTH_SERVICE_URL', 'http://127.0.0.1:8002'), '/');
        $this->reportServiceUrl = rtrim(env('REPORT_SERVICE_URL', 'http://127.0.0.1:8003'), '/');
        $this->notificationServiceUrl = rtrim(env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8005'), '/');
    }

    /**
     * Meneruskan request ke Auth Service (Port 8002)
     */
    public function forwardAuth(Request $request, string $path = '')
    {
        $targetUrl = $this->authServiceUrl . '/api/' . ltrim($path, '/');
        return $this->proxyRequest($request, $targetUrl);
    }

    /**
     * Meneruskan request ke Report Service (Port 8003)
     */
    public function forwardReport(Request $request, string $path = '')
    {
        $targetUrl = $this->reportServiceUrl . '/api/' . ltrim($path, '/');
        return $this->proxyRequest($request, $targetUrl);
    }

    /**
     * Meneruskan request ke Notification Service (Port 8005)
     */
    public function forwardNotification(Request $request, string $path = '')
    {
        $targetUrl = $this->notificationServiceUrl . '/api/' . ltrim($path, '/');
        return $this->proxyRequest($request, $targetUrl);
    }

    /**
     * Engine Proxy HTTP Universal (mendukung JSON dan File Upload Multipart)
     */
    protected function proxyRequest(Request $request, string $targetUrl)
    {
        $method = strtoupper($request->method());
        $headers = [
            'Accept' => 'application/json',
        ];

        if ($request->bearerToken()) {
            $headers['Authorization'] = 'Bearer ' . $request->bearerToken();
        } elseif ($request->header('Authorization')) {
            $headers['Authorization'] = $request->header('Authorization');
        }

        try {
            $client = Http::timeout(25)->withHeaders($headers);

            // Jika ada file upload (multipart/form-data)
            if ($request->hasFile('foto_bukti')) {
                $file = $request->file('foto_bukti');
                $client = $client->attach(
                    'foto_bukti',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );

                // Lampirkan form data lainnya
                $response = $client->post($targetUrl, $request->except(['foto_bukti']));
                return response($response->body(), $response->status(), [
                    'Content-Type' => $response->header('Content-Type') ?? 'application/json',
                ]);
            }

            // Normal JSON / Query request
            $response = match ($method) {
                'GET'    => $client->get($targetUrl, $request->query()),
                'POST'   => $client->post($targetUrl, $request->all()),
                'PUT'    => $client->put($targetUrl, $request->all()),
                'PATCH'  => $client->patch($targetUrl, $request->all()),
                'DELETE' => $client->delete($targetUrl, $request->all()),
                default  => $client->send($method, $targetUrl, ['json' => $request->all()]),
            };

            return response($response->body(), $response->status(), [
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'API Gateway gagal menghubungi target service.',
                'error'   => $e->getMessage(),
                'target'  => $targetUrl,
            ], 503);
        }
    }
}
