<?php

namespace App\Services\ProxyHost;

use App\Enums\CoinType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

/**
 * @deprecated
 */
class ProxyHostApiService
{
    protected Client $client;

    protected string $baseUrl;

    protected string $authToken;


    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function init(): static
    {
        $this->baseUrl = env('PROXY_HOST_API_HOST') . '/api';
        $this->checkToken();
        return $this;
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function checkToken(): void
    {
        try {
            $response = $this->client->post($this->baseUrl . '/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAuthToken(),
                ]
            ]);
        }catch (ClientException $exception) {
            $this->login();
        }
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function login(): array
    {
        $response = $this->client->post($this->baseUrl . '/login', [
            'json' => [
                'email' => env('PROXY_HOST_API_USERNAME'),
                'password' => env('PROXY_HOST_API_PASSWORD'),
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        $token = $body['access_token'] ?? '';

        Cache::put('proxy_host_api_token', $token, now()->addMinutes(1440));
        return $body;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function logout(): array
    {
        $response = $this->client->post($this->baseUrl . '/logout', $this->getHeaders());
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getContainers(string $host, string $username, string $password): array
    {
        $response = $this->client->get(
            "{$this->baseUrl}/proxies?host={$host}&username={$username}&password={$password}",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);
        $containers = [];

        if(isset($data['status']) && $data['status'] == 'success') {
            $containers = $data['body']['containers'] ?? [];
        }

        return $containers;
    }

    public function addProxy(string $host, string $username, string $password, array $proxy): array
    {
        $requestBody = [
            'host' => $host,
            'username' => $username,
            'password' => $password,
            'proxy' => $proxy,
        ];

        $response = $this->client->post(
            "{$this->baseUrl}/proxies/add",
            [
                ...$this->getHeaders(),
                'json' => $requestBody
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);
        $container = [];

        if(isset($data['status']) && $data['status'] == 'success') {
            $container = $data['body']['container'] ?? [];
        }

        return $container;
    }

    public function deleteProxy(string $host, string $username, string $password, string $containerId): array
    {
        $requestBody = [
            'host' => $host,
            'username' => $username,
            'password' => $password,
            'id' => $containerId,
        ];

        $response = $this->client->delete(
            "{$this->baseUrl}/proxies/delete",
            [
                ...$this->getHeaders(),
                'json' => $requestBody
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getFreePort(string $host, string $username, string $password): int
    {
        $response = $this->client->get(
            "{$this->baseUrl}/free-port?host={$host}&username={$username}&password={$password}",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);
        $port = [];

        if(isset($data['status']) && $data['status'] == 'success') {
            $port = $data['body']['free_port'];
        }

        if(empty($port)) {
            throw new \Exception('No free port found');
        }

        return $port;
    }

    /**
     * @return string|null
     */
    protected function getAuthToken(): ?string
    {
        return Cache::get('proxy_host_api_token');
    }

    protected function getHeaders(): array
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
                'Accept' => 'application/json'
            ],
        ];
    }
}
