<?php

namespace App\Services\ProxyHost;

use App\Exceptions\EmptySock5CredentialsException;
use App\Models\Proxy;
use App\Models\User;
use App\Services\ProxyHost\ProxyHostApiService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * @deprecated
 */
class ProxyHostService
{
    protected ProxyHostApiService $apiClient;
    protected Proxy $proxy;

    /**
     * @throws GuzzleException
     */
    private function __construct()
    {
        $this->apiClient = (new ProxyHostApiService())->init();
    }

    /**
     * @throws GuzzleException
     */
    public static function make(Proxy $proxy): static
    {
        $service = new static();
        $service->proxy = $proxy;
        return $service;
    }

    /**
     * @throws \Exception
     */
    public function hostProxy(): array
    {
        return $this->runHostProxy();
    }

    /**
     * @throws EmptySock5CredentialsException
     * @throws \Exception
     */
    public function hostProxyForUser(User $user): array
    {
        if(empty($user->sock5_username) || empty($user->sock5_password)) {
            throw new EmptySock5CredentialsException();
        }

        return $this->runHostProxy($user->sock5_username, $user->sock5_password);
    }

    public function unhostProxy(): array
    {
        //dd($this->proxy);

        if(empty($this->proxy->countryIp)) {
            throw new \Exception("Proxy can't be unhosted");
        }

        $container = $this->apiClient->deleteProxy(
            $this->proxy->countryIp->ip,
            $this->proxy->countryIp->username,
            $this->proxy->countryIp->password,
            str_replace("\n", ' ', strip_tags($this->proxy->container_id))
        );

        return $container;
    }

    protected function runHostProxy(string $username = "", string $password = ""): array
    {
        if(empty($this->proxy->countryIp)) {
            throw new \Exception("Proxy can't be hosted");
        }

        $container = $this->apiClient->addProxy(
            $this->proxy->countryIp->ip,
            $this->proxy->countryIp->username,
            $this->proxy->countryIp->password,
            [
                "host" => $this->proxy->countryIp->ip,
                "port" => $this->proxy->port,
                "name" => $this->proxy->name,
                "username" => $username,
                "password" => $password,
            ]
        );

        $this->proxy->container_id = str_replace("\n", ' ', strip_tags($container['containerId']));
        $this->proxy->ping = $container['ping'];
        $this->proxy->save();

        return $container;
    }
}
