<?php

namespace App\Services\ProxyHost;

use App\Models\CountryIp;
use App\Models\Proxy;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * @deprecated
 */
class ProxyHostWebhooksService
{
    const URL_PREFIX = '/hooks';

    const PORT = 9000;

    const CHECK_URL = 'https://check-host.net/ip';


    protected Client $client;

    protected string $prefix;

    public function __construct()
    {
        $this->client = new Client();
        $this->prefix = self::PORT . self::URL_PREFIX;
    }

    // TODO: Realise this webhook
    public function createNewProxy()
    {

    }

    // TODO: Realise this webhook
    public function deleteProxy(Proxy $proxy)
    {

    }

    public function getFreePorts(CountryIp $host): array
    {
        $ip = $host->ip;
        $ports = [];

        $response = $this->client->post(
            "{$ip}:{$this->prefix}/get_ports",
            [
                ...$this->getHeaders(),
                'json' => [
                    'ip' => $ip,
                ]
            ]
        );
        $data = json_decode($response->getBody()->getContents(), true);

        if(isset($data['ports'])) {
            $ports = array_filter($data['ports'], 'is_numeric');
        }

        return $ports;
    }

    public function addUserToProxy(Proxy $proxy, User $user): void
    {
        $host = $proxy->countryIp;
        $ip = $host->ip;

//        $this->client->post(
//            "{$ip}:{$this->prefix}/auth-user",
//            [
//                ...$this->getHeaders(),
//                'json' => [
//                    "user" => $user->socks5_username,
//                    "proxy" => "{$proxy->ip_shown}:{$proxy->port}",
//                    "pass" => $user->socks5_password
//                ]
//            ]
//        );
    }

    public function removeUserFromProxy(Proxy $proxy, User $user): void
    {
        $host = $proxy->countryIp;
        $ip = $host->ip;

        $this->client->post(
            "{$ip}:{$this->prefix}/deauth-user",
            [
                ...$this->getHeaders(),
                'json' => [
                    "user" => $user->socks5_username,
                    "proxy" => "{$proxy->ip_shown}:{$proxy->port}",
                    "pass" => $user->socks5_password
                ]
            ]
        );
    }

    public function checkProxyResponseTime($proxy): array
    {
        $proxyHost = $proxy->ip_shown;
        $proxyPort = $proxy->port;
        $proxyUser = $this->user->socks5_username;
        $proxyPass = $this->user->socks5_password;

        $ch = curl_init(self::CHECK_URL);

        curl_setopt($ch, CURLOPT_PROXY, "{$proxyHost}:{$proxyPort}");
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$proxyUser}:{$proxyPass}");
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $startTime = microtime(true);
        curl_exec($ch);
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        curl_close($ch);

        return [
           'time' => $executionTime,
            'unit' => 's'
        ];
    }

    protected function getHeaders(): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ];
    }
}
