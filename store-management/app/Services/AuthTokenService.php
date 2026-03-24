<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AuthTokenService
{
    public function getToken(): string

    {
        return Cache::remember('external_api_token', now()->addMinutes(50), function () {
            $response = Http::asForm()->post(config('services.external_transfer_api.auth_url'), [
                'grant_type' => config('services.external_transfer_api.grant_type'),
                'username' => config('services.external_transfer_api.username'),
                'password' => config('services.external_transfer_api.password'),
                'scope' => config('services.external_transfer_api.scope'),
                'client_id' => config('services.external_transfer_api.client_id'),
                'client_secret' => config('services.external_transfer_api.client_secret'),
            ]);

            $response->throw();

            $data = $response->json();

            if (!isset($data['access_token'])) {
                throw new \Exception('No access_token found.');
            }

            return $data['access_token'];
        });
    }
}