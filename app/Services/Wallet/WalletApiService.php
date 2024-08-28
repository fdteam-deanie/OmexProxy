<?php

namespace App\Services\Wallet;

use App\Enums\CoinType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class WalletApiService
{

    private string $wallet;

    private Client $client;

    private string $hostName;


    /**
     * @throws GuzzleException
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->hostName = env('CRYPTO_WALLET_API_HOST') . '/api';
        $this->checkToken();
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function login(): array
    {
        $response = $this->client->post($this->hostName . '/login', [
            'json' => [
                'email' => env('CRYPTO_WALLET_API_USERNAME'),
                'password' => env('CRYPTO_WALLET_API_PASSWORD'),
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        $token = $body['access_token'] ?? '';

        Cache::put('wallet_api_token', $token, now()->addMinutes(1440));
        return $body;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function logout(): array
    {
        $response = $this->client->post($this->hostName . '/logout', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function checkToken(): void
    {
        try {
            $response = $this->client->post($this->hostName . '/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAuthToken(),
                ]
            ]);
        }catch (ClientException $exception) {
            $this->login();
        }
    }

    /**
     * @param CoinType $coinType
     * @return mixed
     * @throws GuzzleException
     */
    public function createWallet(CoinType $coinType): array
    {
        $response = $this->client->post("{$this->hostName}/wallet/{$coinType->value}/generate-wallet", [
            ...$this->getHeaders(),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param CoinType $coinType
     * @return mixed
     * @throws GuzzleException
     */
    public function createNewAddress(CoinType $coinType , $userName = null): array
    {
        $requestBody = !is_null($userName) ? [ 'user_name' => $userName ] : [];
        $response = $this->client->post("{$this->hostName}/wallet/{$coinType->value}/create-address", [
            ...$this->getHeaders(),
            'body' => json_encode($requestBody)
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param CoinType $coinType
     * @return array
     * @throws GuzzleException
     */
    public function getBalanceByCoinType(CoinType $coinType): array
    {
        $response = $this->client->get("{$this->hostName}/wallet/{$coinType->value}/balance", [
            ...$this->getHeaders(),
            'query' => [
                'wallet' => $this->wallet
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * @param string $currency
     * @param int|null $from
     * @return array
     * @throws GuzzleException
     */
    public function getTransactionsByCurrency(string $currency, int|null $from = null): array
    {
        $query = [ 'wallet' => $this->wallet];
        if ($from) {
            $query['from'] = $from;
        }

        $response = $this->client->get("{$this->hostName}/wallet/{$currency}/transactions", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
            ],
            'query' => $query
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getTransactionById(string $currency, string $id): array
    {
        $response = $this->client->get("{$this->hostName}/wallet/{$currency}/transaction/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
            ],
            'query' => [
                'wallet' => $this->wallet
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function createTransactionAddress(CoinType $coinType)
    {
        $response = $this->client->post("{$this->hostName}/wallet/{$coinType->value}/generate", [
            'headers' => $this->getHeaders(),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param CoinType $currency
     * @param $amount
     * @param string|null $address
     * @return array
     * @throws GuzzleException
     */
    public function withdraw (CoinType $coinType, $amount, string|null $address = null): array
    {
        $body = [ 'amount' => $amount ];
        if (!is_null($address)) {
            $body['address'] = $address;
        }

        $response = $this->client->put("{$this->hostName}/wallet/{$coinType->value}/withdraw", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
                'Accept' => 'application/json'
            ],
            'json' => $body,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


    public function getWallet(): CoinType
    {
        return $this->wallet;
    }

    /**
     * @param string $wallet
     * @return WalletApiService
     */
    public function setWallet(string $wallet): self
    {
        $this->wallet = $wallet;
        return $this;
    }

    /**
     * @return string|null
     */
    protected function getAuthToken(): ?string
    {
        return Cache::get('wallet_api_token');
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
