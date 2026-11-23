<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GatewayClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('API_GATEWAY_URL', 'http://127.0.0.1:8001/api'), '/');
    }

    protected function client()
    {
        $client = Http::timeout(20)->withHeaders([
            'Accept' => 'application/json',
        ]);

        if (session()->has('admin_token')) {
            $client = $client->withToken(session('admin_token'));
        }

        return $client;
    }

    public function get(string $endpoint, array $query = [])
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->client()->get($url, $query);
    }

    public function post(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->client()->post($url, $data);
    }

    public function put(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->client()->put($url, $data);
    }

    public function delete(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->client()->delete($url, $data);
    }

    public function postMultipart(string $endpoint, array $data, $file = null, string $fileParam = 'foto_bukti')
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        $client = $this->client();

        if ($file && $file->isValid()) {
            $client = $client->attach(
                $fileParam,
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            );
        }

        return $client->post($url, $data);
    }
}
