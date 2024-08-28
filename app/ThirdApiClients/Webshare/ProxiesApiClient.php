<?php

namespace App\ThirdApiClients\Webshare;

use App\ThirdApiClients\Webshare\Responses\ProxyConfigResponse;

class ProxiesApiClient extends BaseWebshareApiClient
{
    public function getProxyList(int $page = 1, int $perPage = 50, string $mode = 'direct'): array
    {
        $response = $this->client->get(
            "{$this->baseUrl}/proxy/list/?mode=$mode&page=$page&page_size=$perPage",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function getProxiesInChunks(callable $callback, int $chunkSize = 50): void
    {
        $page = 1;
        $data = $this->getProxyList($page, $chunkSize);
        $count = $data['count'] ?? 0;
        $proxies = $data['results'];
        $callback($proxies);
        while ($count > 0) {
            $page++;
            $data = $this->getProxyList($page, $chunkSize);
            $count = $data['count'] ?? 0;
            $proxies = $data['results'];
            $callback($proxies);
        }
    }

    public function getProxyConfig(?string $subUserId = null): ProxyConfigResponse
    {
        $response = $this->client->get(
            "{$this->baseUrl}/proxy/config",
            $this->getHeaders($subUserId)
        );

        if($response->getStatusCode() != 200) {
            throw new \Exception('Webshare API error: '.$response->getBody()->getContents());
        }

        $data = json_decode($response->getBody()->getContents(), true);
        return ProxyConfigResponse::fromArray($data);
    }

    public function updateProxyConfig(array $data, ?string $subUserId = null): void
    {
        $response = $this->client->patch(
            "{$this->baseUrl}/proxy/config/", [
                ...$this->getHeaders($subUserId),
                "json" => $data
            ]
        );

        if($response->getStatusCode() != 200) {
            throw new \Exception('Webshare API error: '.$response->getBody()->getContents());
        }
    }

    public function getProxyListDownloadLink(string $token): string
    {
        return "{$this->baseUrl}/proxy/list/download/$token/-/any/username/ip/-/";
    }
}
