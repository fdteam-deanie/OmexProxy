<?php

namespace App\ProxyProviders;

use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Proxy;
use App\Models\ProxyISP;
use App\Models\ProxyOrg;
use App\Models\ProxyType;
use App\Models\Rate;
use App\Models\User;
use App\Models\ZIP;
use App\Services\ProxyPriceService;
use App\ThirdApiClients\IpQualityScore\IpQualityScoreApiClient;
use App\ThirdApiClients\WhoIs\Response;
use App\ThirdApiClients\WhoIs\WhoIsApiClient;
use Illuminate\Support\Facades\DB;

abstract class BaseProxyProvider implements ProxyProvider
{
    const PROVIDER_KEY = '';

    protected WhoIsApiClient $whoIsApiClient;
    protected IpQualityScoreApiClient $ipQualityScoreApiClient;
    protected ProxyPriceService $proxyPriceService;

    public function __construct()
    {
        $this->whoIsApiClient = new WhoIsApiClient();
        $this->ipQualityScoreApiClient = new IpQualityScoreApiClient();
        $this->proxyPriceService = new ProxyPriceService();
    }

    /**
     * @throws \Exception
     */
    public function createProxy(string $ip, int $port, ?string $id = null): ?Proxy
    {
        $proxy = ProxyBuilder::init()
            ->setIp($ip)
            ->setPort($port)
            ->setProvider(static::PROVIDER_KEY)
            ->setStatic(true)
            ->setProviderProxyId($id)
            ->build();

        $proxy->save();
        return $proxy;
    }

    public function fillAdditionalInfo(Proxy $proxy, string $type = 'ISP'): Proxy
    {
        $ipInfo = $this->whoIsApiClient->getIpInfo($proxy->ip_shown);

        $proxy = $this->fillAdditionProxyInfo($ipInfo, $proxy, 0, $type);
        return $this->updatePrice($proxy);
    }

    public function fillAdditionProxyInfo(Response $ipInfo, $proxy, float $price = 0, string $type = 'ISP'): Proxy
    {
        $country = $this->getCountryByCode(
            $ipInfo->getCountry()->getCode(),
            $ipInfo->getRegion()->getCode()
        );

        if(empty($country)) {
            return $proxy;
        }

        $speed = rand($country->min_speed, $country->max_speed);
        $ping = rand($country->min_ping, $country->max_ping);

        $org = $this->getOrgByName($ipInfo->getConnection()->getOrg());
        $isp = $this->getIspByName($ipInfo->getConnection()->getIsp());
        $city = $this->getCityByName($ipInfo->getCity(), $country);
        $zip = $this->getZipByCode($ipInfo->getZip(), $country);
        $type = $this->getProxyTypeByName($type);

        $proxy = ProxyBuilder::fromProxy($proxy)
            ->setDomain($ipInfo->getConnection()->getDomain())
            ->setSpeed($speed)
            ->setPing($ping)
            ->setContinent($country->continent)
            ->setCountry($country)
            ->setCity($city)
            ->setOrg($org)
            ->setIsp($isp)
            ->setZip($zip)
            ->setType($type)
            ->setPrice($price)
            ->build();

        return $proxy;
    }

    public function updatePrice(Proxy $proxy)
    {
        $rate = Rate::where('days', '>=', 0)->orderBy('days', 'asc')->first();

        $this->proxyPriceService->updatePriceByRate($proxy, $rate);

        return $proxy;
    }

    public function checkFraudScore(Proxy $proxy): Proxy
    {
        $fraudScore = $this->ipQualityScoreApiClient->getIpQualityInfo($proxy->ip_shown)->getFraudScore();
        $proxy->fraud_score = $fraudScore;

        if($fraudScore >= 40) {
            $proxy->is_active = true;
        }

        $proxy->save();

        return $proxy;
    }

    protected function getCountryByCode(string $countryCode, ?string $regionCode = null): ?Country
    {
        if(strtolower($countryCode) == 'us') {
            return $this->getUsaStateByCode($regionCode);
        }

        $country = Country::where(
            DB::raw('lower(code)'),
            'like',
            '%' . strtolower($countryCode) . '%'
        )->first();

        if(empty($country)) {
            return null;
        }

        return $country;
    }

    protected function getUsaStateByCode(string $code): ?Country
    {
        $usa = Continent::where('name', 'USA')->first();

        $state = Country::where(
            DB::raw('lower(name)'),
            'like',
            strtolower($code)
        )
            ->where('continent_id', $usa->id)
            ->first();

        if(empty($state)) {
            $state = Country::create(
                [
                    'name' => strtoupper($code),
                    'code' => null,
                    'continent_id' => $usa->id,
                ]
            );
        }

        return $state;
    }

    protected function getOrgByName(string $name): ProxyOrg
    {
        $org = ProxyOrg::where(
            DB::raw('lower(name)'),
            'like',
            '%' . strtolower($name) . '%'
        )->first();

        if(empty($org)) {
            $org = ProxyOrg::create(['name' => $name]);
        }

        return $org;
    }

    protected function getIspByName(string $name): ProxyISP
    {
        $isp = ProxyISP::where(
            DB::raw('lower(name)'),
            'like',
            '%' . strtolower($name) . '%'
        )->first();

        if(empty($isp)) {
            $isp = ProxyISP::create(['name' => $name]);
        }

        return $isp;
    }

    protected function getZipByCode(string $code, Country $country): ?ZIP
    {
        $zip = ZIP::where(
            DB::raw('lower(name)'),
            'like',
            '%' . strtolower($code) . '%'
        )->first();

        if(empty($zip)) {
            $zip = ZIP::create(
                [
                    'name' => $code,
                    'country_id' => $country->id,
                ]
            );
        }

        return $zip;
    }

    protected function getCityByName(string $name, Country $country): ?City
    {
        $city = City::where(
            DB::raw('lower(name)'),
            'like',
            '%' . strtolower($name) . '%'
        )->first();

        if(empty($city)) {
            $city = City::create(
                [
                    'name' => $name,
                    'country_id' => $country->id,
                ]
            );
        }

        return $city;
    }

    protected function getProxyTypeByName(string $name): ProxyType
    {
        $type = ProxyType::where(
            DB::raw('lower(name)'),
            'like',
            '%' . strtolower($name) . '%'
        )->first();

        if(empty($type)) {
            $type = ProxyType::create(['name' => $name]);
        }

        return $type;
    }

    protected function getProxyById(string $id): ?Proxy
    {
        return Proxy::where('provider', self::PROVIDER_KEY)
            ->where('provider_proxy_id', $id)
            ->first();
    }

    public function grantUserAccessToProxy(User $user, Proxy $proxy): void
    {

    }

    public function revokeUserAccessToProxy(User $user, Proxy $proxy): void
    {

    }

    public function getAuthCredentials(Proxy $proxy, User $user): array
    {
        return [
            'username' => $user->socks5_username,
            'password' => $user->socks5_password,
        ];
    }

    public function getConnectionHost(Proxy $proxy): string
    {
        return "$proxy->ip_shown:$proxy->port";
    }
}
