<?php

namespace App\ProxyProviders\Webshare;

use App\Models\Proxy;
use App\Models\User;
use App\ProxyProviders\BaseProxyProvider;
use App\ProxyProviders\ProxyBuilder;
use App\ThirdApiClients\Webshare\ProxiesApiClient;
use App\ThirdApiClients\WhoIs\Response;
use App\ThirdApiClients\WhoIs\WhoIsApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Storage;

class WebshareRotatingProxyProvider extends BaseProxyProvider
{
    const PROVIDER_KEY = 'webshare-rotating';

    protected ProxiesApiClient $webshareApiClient;

    protected string $username = '';
    protected string $password = '';

    public function __construct()
    {
        $this->webshareApiClient = ProxiesApiClient::make('support');
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function importProxies(): void
    {
//        $config = $this->webshareApiClient->getProxyConfig();
//        $downloadToken = $config->getProxyListDownloadToken();
//        $url = $this->webshareApiClient->getProxyListDownloadLink($downloadToken);

//        $contents = file_get_contents($url);
//        Storage::disk('local')->put('proxies.txt', $contents);

//        dd('ok');

//        $lines = file($url);
//
//        $proxyInsertData = [];
//
//        foreach($lines as $index => $line) {
//            $data = explode(":", $line);
//            $ip = $data[0];
//            $port = $data[1];
//
//            $proxyInsertData["$ip:$port"] = [
//                'ip_shown' => $ip,
//                'port' => $port,
//                'provider_proxy_id' => $index
//            ];
//        }
//
//        foreach ($proxyInsertData as $proxyData) {
//            $proxy = Proxy::firstOrCreate(
//                [
//                    'ip_shown' => $proxyData['ip_shown'],
//                    'port' => $proxyData['port'],
//                    'provider' => static::PROVIDER_KEY,
//                ],
//                [
//                    'provider_proxy_id' => $proxyData['provider_proxy_id'],
//                    'max_users_count' => 100
//                ],
//            );
//        }

        $importCountries = [
            'ua', 'ca', 'us', 'gb', 'de', 'fr', 'it', 'es', 'nl', 'pl', 'ru', 'se', 'ch', 'no', 'fi',
            'dk', 'cz', 'sk', 'hu', 'ro', 'bg', 'gr', 'tr', 'sa', 'ae', 'in', 'sg', 'au', 'nz',
            'jp', 'kr', 'tw', 'hk', 'vn', 'my', 'th', 'id', 'ph', 'br', 'mx', 'ar', 'co', 'pe', 'cl'
        ];

        $config = $this->webshareApiClient->getProxyConfig();

        $this->username = $config->getUsername();
        $this->password = $config->getPassword();

        $countries = $config->getCountries()->keys();

        foreach ($countries as $index => $country) {
            $code = strtolower($country);
            if (!in_array($code, $importCountries)) {
                continue;
            }
            $this->importProxiesByCountry($code, 100);
        }
    }


    /**
     * @throws \Exception
     */
    public function importProxiesByCountry(string $country, ?int $limit = null)
    {
        $counter = 1;
        while (true) {
            if (!is_null($limit) && $counter > $limit) {
                break;
            }

            $response = $this->getProxy($country, $counter);

            if (is_null($response)) {
                break;
            }

            $proxy = Proxy::firstOrNew(
                [
                    'provider_proxy_id' => "{$country}-{$counter}",
                ],
                [
                    'ip_shown' => $response->getIp(),
                    'is_static' => false,
                    'provider' => static::PROVIDER_KEY,
                    'port' => 80,
                    'max_users_count' => 100
                ],
            );

            $proxy = $this->fillAdditionProxyInfo($response, $proxy, 1.5);
            $proxy->save();

            $counter++;
        }
    }

    /**
     * @throws \Exception
     */
    public function getProxy(string $country, string $index): ?Response
    {
        $client = new Client([
            RequestOptions::PROXY => [
                'http' => "http://$this->username-$country-$index:$this->password@p.webshare.io",
                'https' => "http://$this->username-$country-$index:$this->password@p.webshare.io"
            ]
        ]);
        $api = new WhoIsApiClient($client);
        try {
            return $api->getCurrentIpInfo();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAuthCredentials(Proxy $proxy, User $user): array
    {
        $config = $this->webshareApiClient->getProxyConfig();

        $this->username = $config->getUsername();
        $this->password = $config->getPassword();

        return [
            'username' => "$this->username-$proxy->provider_proxy_id",
            'password' => $this->password
        ];
    }

    public function getConnectionHost(Proxy $proxy): string
    {
        return "185.76.11.213:80";
    }
}
