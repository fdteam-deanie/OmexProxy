<?php

namespace App\ThirdApiClients\Webshare;

use GuzzleHttp\Client;

class BaseWebshareApiClient
{
    const BASE_URL = 'https://proxy.webshare.io/api/v2';

    protected Client $client;

    protected string $baseUrl;

    protected string $authToken;

    private function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.webshare.host', self::BASE_URL);
    }

    public static function make(?string $account = 'default'): static
    {
        $client = new static();
        if(config()->has('services.webshare.accounts.'.$account)) {
            $key = config('services.webshare.accounts.'.$account.'.token');
        } else {
            $key = config('services.webshare.accounts.default.token');
        }
        $client->authToken = $key;
        return $client;
    }

    protected function getHeaders(?string $subUserId = null): array
    {
        $headers = [
            'Authorization' => 'Token '.$this->authToken,
            'Accept' => 'application/json'
        ];
        if($subUserId) {
            $headers['X-Subuser'] = $subUserId;
        }
        return [
            'headers' => $headers
        ];
    }
}
