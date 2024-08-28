<?php

namespace App\ThirdApiClients\IpQualityScore;

use GuzzleHttp\Client;

class IpQualityScoreApiClient
{
    const BASE_URL = 'https://ipqualityscore.com/api/json/ip';

    protected string $baseUrl;

    protected string $apiKey;

    protected Client $client;

    public function __construct()
    {
        $this->baseUrl = env('IP_QUALITY_SCORE_API_HOST', self::BASE_URL);
        $this->apiKey = env('IP_QUALITY_SCORE_API_KEY', '');
        $this->client = new Client();
    }

    public function getIpQualityInfo(string $ip): Response
    {
        $response = $this->client->get(
            "{$this->baseUrl}/{$this->apiKey}/$ip",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if($data['success'] == false) {
            throw new \Exception($data['message']);
        }

        return new Response($data['fraud_score'] ?? null);
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
