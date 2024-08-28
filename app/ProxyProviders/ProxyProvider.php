<?php

namespace App\ProxyProviders;

use App\Models\Proxy;
use App\Models\User;

interface ProxyProvider
{
    public function importProxies(): void;

    public function grantUserAccessToProxy(User $user, Proxy $proxy): void;

    public function revokeUserAccessToProxy(User $user, Proxy $proxy): void;

    public function getAuthCredentials(Proxy $proxy, User $user): array;
}
