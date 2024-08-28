<?php

namespace App\ProxyProviders;

use App\ProxyProviders\Webshare\WebshareRotatingProxyProvider;
use App\ProxyProviders\Webshare\WebshareStaticProxyProvider;

class ProxyProviderFactory
{
    protected static $providers = [
        'webshare-static' => WebshareStaticProxyProvider::class,
        'webshare-rotating' => WebshareRotatingProxyProvider::class
    ];
    public static function make(string $providerKey): ?BaseProxyProvider
    {
        if(!in_array($providerKey, array_keys(static::$providers)))
        {
            return null;
        }
        return new static::$providers[$providerKey];
    }
}
