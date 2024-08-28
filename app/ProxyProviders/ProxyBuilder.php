<?php

namespace App\ProxyProviders;

use App\Models\Proxy;

class ProxyBuilder
{
    protected Proxy $proxy;
    public static function init(): ProxyBuilder
    {
        $builder = new self();
        $builder->proxy = new Proxy();
        $builder->proxy->generateName();
        $builder->proxy->max_users_count = env('MAX_PROXY_USERS_COUNT', 100);

        return $builder;
    }

    public static function fromProxy(Proxy $proxy): ProxyBuilder
    {
        $builder = new self();
        $builder->proxy = $proxy;

        return $builder;
    }

    public function setIp(string $ip): ProxyBuilder
    {
        $this->proxy->ip_shown = $ip;
        return $this;
    }

    public function setPort(int $port): ProxyBuilder
    {
        $this->proxy->port = $port;
        return $this;
    }

    public function setDomain(string $domain): ProxyBuilder
    {
        $this->proxy->domain_shown = $domain;
        return $this;
    }

    public function setStatic(bool $static): ProxyBuilder
    {
        $this->static = $static;
        return $this;
    }

    public function setProvider(string $provider): ProxyBuilder
    {
        $this->proxy->provider = $provider;
        return $this;
    }

    public function setProviderProxyId(?string $providerProxyId): ProxyBuilder
    {
        $this->proxy->provider_proxy_id = $providerProxyId;
        return $this;
    }

    public function setMaxUsersCount(int $maxUsersCount): ProxyBuilder
    {
        $this->proxy->max_users_count = $maxUsersCount;
        return $this;
    }

    public function setContinent($continent): ProxyBuilder
    {
        $this->proxy->continent()->associate($continent);
        return $this;
    }

    public function setCountry($country): ProxyBuilder
    {
        $this->proxy->country()->associate($country);
        return $this;
    }

    public function setCity($city): ProxyBuilder
    {
        $this->proxy->city()->associate($city);
        return $this;
    }

    public function setOrg($org): ProxyBuilder
    {
        $this->proxy->org()->associate($org);
        return $this;
    }

    public function setIsp($isp): ProxyBuilder
    {
        $this->proxy->isp()->associate($isp);
        return $this;
    }

    public function setZip($zip): ProxyBuilder
    {
        $this->proxy->zip()->associate($zip);
        return $this;
    }

    public function setType($type): ProxyBuilder
    {
        $this->proxy->type()->associate($type);

        $this->proxy->is_mobile = $type->name == 'MOB';
        $this->proxy->is_residential = $type->name == 'DCH';
        $this->proxy->is_server = $type->name == 'ISP' || $type->name == 'ISP/MOB';

        return $this;
    }

    public function setFraudScore($fraudScore): ProxyBuilder
    {
        $this->proxy->fraud_score = $fraudScore;
        return $this;
    }

    public function setSpeed($speed): ProxyBuilder
    {
        $this->proxy->speed = $speed;
        return $this;
    }

    public function setPing($ping): ProxyBuilder
    {
        $this->proxy->ping = $ping;
        return $this;
    }

    public function setPrice($price): ProxyBuilder
    {
        $this->proxy->price = $price;
        return $this;
    }

    public function build(): Proxy
    {
        return $this->proxy;
    }
}
