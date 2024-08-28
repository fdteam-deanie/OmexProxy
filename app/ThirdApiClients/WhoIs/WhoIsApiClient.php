<?php

namespace App\ThirdApiClients\WhoIs;

use GuzzleHttp\Client;

class WhoIsApiClient
{
    protected string $baseUrl = 'https://ipwhois.pro/';
    protected string $apiKey;
    protected Client $client;

    public function __construct(?Client $client = null)
    {
        if(is_null($client)) {
            $client = new Client();
        }
        $this->apiKey = env('WHO_IS_API_KEY');
        $this->client = $client;
    }
    public function getIpInfo(string $ip): Response
    {
        $response = $this->client->get(
            "{$this->baseUrl}/$ip?key={$this->apiKey}",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if($data['success'] == false) {
            throw new \Exception($data['message']);
        }

        return $this->wrapResponse($data);
    }

    public function getCurrentIpInfo(): Response
    {
        $response = $this->client->get(
            "{$this->baseUrl}?key={$this->apiKey}",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if($data['success'] == false) {
            throw new \Exception($data['message']);
        }

        return $this->wrapResponse($data);
    }

    protected function wrapResponse(array $data): Response
    {
        $continent = new Continent(
            $data['continent'],
            $data['continent_code']
        );

        $country = new Country(
            $data['country'],
            $data['country_code']
        );

        $region = new Region(
            $data['region'],
            $data['region_code']
        );

        $connection = new Connection(
            $data['connection']['domain'],
            $data['connection']['org'],
            $data['connection']['isp']
        );

        return new Response(
            $data['ip'],
            $continent,
            $country,
            $region,
            $data['city'],
            $data['postal'],
            $connection
        );
    }

    protected function getHeaders(): array
    {
        return [
            'headers' => [
                'Accept' => 'application/json'
            ],
        ];
    }
}
