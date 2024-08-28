<?php

namespace App\ProxyProviders\Webshare;

use App\Models\Proxy;
use App\Models\User;
use App\Models\Webshare\WebshareSubUser;
use App\ProxyProviders\BaseProxyProvider;
use App\ThirdApiClients\Webshare\ProxiesApiClient;
use App\ThirdApiClients\Webshare\SubUsersApiClient;
use Illuminate\Support\Facades\Log;

class WebshareStaticProxyProvider extends BaseProxyProvider
{
    const PROVIDER_KEY = 'webshare-static';

    protected ProxiesApiClient $proxiesApiClient;
    protected SubUsersApiClient $subUsersApiClient;


    public function __construct()
    {
        $this->proxiesApiClient = ProxiesApiClient::make();
        $this->subUsersApiClient = SubUsersApiClient::make();
        parent::__construct();
    }
    public function importProxies(): void
    {
        $this->proxiesApiClient->getProxiesInChunks(function ($proxies) {
            foreach ($proxies as $index => $proxyData) {
                $ip = $proxyData['proxy_address'];
                $port = $proxyData['port'];
                $id = $proxyData['id'];

                $proxy = $this->getProxyById($id);

                if (empty($proxy)) {
                    try {
                        $proxy = $this->createProxy($ip, $port, $id);
                        $this->fillAdditionalInfo($proxy);
                    } catch (\Exception $e) {
                        Log::info($e->getMessage());
                        continue;
                    }
                }
            }
        });
    }
        /**
     * @throws \Exception
     */
    public function grantUserAccessToProxy(User $user, Proxy $proxy): void
    {
        $this->updateSubuserAccess($user);
    }

    /**
     * @throws \Exception
     */
    public function revokeUserAccessToProxy(User $user, Proxy $proxy): void
    {
        $this->updateSubuserAccess($user);
    }

    /**
     * @throws \Exception
     */
    protected function updateSubuserAccess(User $user): void
    {
        $subUser = $this->getSubUser($user);
        $countries = $this->getAvailableCountries($user);

        $proxyCount = array_sum($countries);

        $this->subUsersApiClient->updateSubUser($subUser->id, [
            "proxy_limit" => 0,
            "max_thread_count" => 500,
            "proxy_countries" => $countries
        ]);

        if($proxyCount == 0) {
            $subUser->user_id = null;
            $subUser->save();
        }
    }

    protected function getAvailableCountries(User $user): array
    {
        $webshareProxies = $user->proxies()
            ->with('country')
            ->with('continent')
            ->where('provider', static::PROVIDER_KEY)
            ->wherePivot('expired_at', '>', now())
            ->get();

        $countries = [];
        foreach ($webshareProxies as $webshareProxy) {
            $code = $webshareProxy->country->code;
            if($webshareProxy->continent->name === 'USA') {
                $code = 'US';
            }
            if(isset($countries[$code])) {
                $countries[$code]++;
            } else {
                $countries[$code] = 1;
            }
        }
        if(empty($countries)) {
            $countries = [
                'ZZ' => 0
            ];
        }
        return $countries;
    }

    /**
     * @throws \Exception
     */
    protected function getSubUser(User $user): ?WebshareSubUser
    {
        $subUser = WebshareSubUser::where('user_id', $user->id)->first();

        if(empty($subUser)) {
            $subUser = WebshareSubUser::where('user_id', null)->first();
            if(empty($subUser)) {
                $subUser = $this->createSubUser($user);
            } else {
                $this->connectSubUser($user, $subUser);
            }
        }

        return $subUser;
    }

    protected function createSubUser(User $user): WebshareSubUser
    {
        $subUser = $this->subUsersApiClient->createSubUser([
            "label" => $user->id,
            "proxy_limit" => 0,
            "max_thread_count" => 500
        ]);

        $this->proxiesApiClient->updateProxyConfig([
            "username" => $user->socks5_username,
            "password" => $user->socks5_password
        ], $subUser['id']);

        return WebshareSubUser::create([
            'id' => $subUser['id'],
            'user_id' => $user->id
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function connectSubUser(User $user, WebshareSubUser $subUser): void
    {
        $subUser->user_id = $user->id;
        $subUser->save();

        $this->subUsersApiClient->updateSubUser($subUser->id, [
            "label" => $user->id
        ]);

        $this->proxiesApiClient->updateProxyConfig([
            "username" => $user->socks5_username,
            "password" => $user->socks5_password
        ], $subUser->id);
    }
}
