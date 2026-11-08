<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnujaPortalService
{
    protected string $baseUrl;
    protected ?string $xToken;
    protected ?string $devId;
    protected ?string $staticToken;

    public function __construct()
    {
        $this->baseUrl     = config('services.unuja.base_url', 'https://sso.unuja.ac.id');
        $this->xToken      = config('services.unuja.x_token');
        $this->devId       = config('services.unuja.dev_id');
        $this->staticToken = config('services.unuja.token');
    }

    /**
     * Authorize data access and retrieve the active session token.
     * Endpoint: POST /portal/data/authorize
     */
    public function authorize(): ?string
    {
        if (empty($this->xToken) || empty($this->devId)) {
            return null;
        }

        // Check if token is already cached
        $cacheKey = 'unuja_portal_session_token_' . md5($this->xToken . $this->devId);
        if ($cachedToken = Cache::get($cacheKey)) {
            return $cachedToken;
        }

        try {
            $endpoint = rtrim($this->baseUrl, '/') . '/portal/data/authorize';

            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                    'User-Agent'   => config('app.name', 'LabReport') . '-Microservice',
                ])
                ->post($endpoint, [
                    'X-Token' => $this->xToken,
                    'dev_id'  => $this->devId,
                ]);

            if ($response->successful()) {
                $body = $response->json();

                // Extract token from response data (e.g., token field or session token)
                $token = $body['token'] ?? $body['data']['token'] ?? $body['data']['session_token'] ?? null;

                if (! $token && isset($body['data']['info']['waktu_berlaku'])) {
                    // Fallback to xToken if authorize succeeds but token is implicit
                    $token = $this->xToken;
                }

                if ($token) {
                    Cache::put($cacheKey, $token, now()->addHours(6));
                    return $token;
                }
            }

            Log::warning('UNUJA Portal authorize failed: ' . $response->status(), [
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('UNUJA Portal authorize exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get reference data from UNUJA SSO Portal API.
     * Endpoint: POST /portal/data/data/{token}
     *
     * @param string $filter 'mahasiswa', 'dosen', 'karyawan', etc.
     * @param string $search Keyword search (NIM, NIP, Nama)
     * @param string $pagination 'on' or 'off'
     * @return array
     */
    public function getReferenceData(string $filter = 'mahasiswa', string $search = '', string $pagination = 'off'): array
    {
        // 1. Determine active session token
        $token = $this->staticToken ?: $this->authorize();

        // If credentials are not set, use graceful mock response for demo
        if (empty($token) || empty($this->xToken)) {
            return $this->getMockData($filter, $search);
        }

        try {
            $endpoint = rtrim($this->baseUrl, '/') . '/portal/data/data/' . $token;

            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Token'      => $this->xToken,
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent'   => config('app.name', 'LabReport') . '-Microservice',
                ])
                ->post($endpoint, [
                    'filter'     => $filter,
                    'pagination' => $pagination,
                    'search'     => $search,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('UNUJA Portal API error: ' . $response->status(), [
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil data dari Portal UNUJA (HTTP ' . $response->status() . ')',
                'data'    => null,
            ];
        } catch (\Throwable $e) {
            Log::error('UNUJA Portal API exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Koneksi ke Portal UNUJA terputus: ' . $e->getMessage(),
                'data'    => null,
            ];
        }
    }

    /**
     * Helper to find a specific Mahasiswa by NIM.
     */
    public function findMahasiswaByNim(string $nim): ?array
    {
        $res = $this->getReferenceData('mahasiswa', $nim);

        if (! empty($res['success']) && ! empty($res['data'])) {
            $list = is_array($res['data']) ? $res['data'] : [];
            foreach ($list as $item) {
                if (isset($item['nim']) && (string) $item['nim'] === (string) $nim) {
                    return $item;
                }
            }
            return $list[0] ?? null;
        }

        return null;
    }

    /**
     * Fallback mock data when API token is not yet configured.
     */
    protected function getMockData(string $filter, string $search): array
    {
        return [
            'success' => true,
            'message' => 'Data referensi (Mode Simulasi - Silakan isi UNUJA_X_TOKEN & UNUJA_DEV_ID di .env)',
            'is_mock' => true,
            'data'    => [
                [
                    'nim'   => $search ?: '2101001',
                    'nama'  => 'Ahmad Fauzi (Mahasiswa UNUJA)',
                    'prodi' => 'Teknik Informatika',
                    'role'  => $filter,
                ],
            ],
        ];
    }
}
