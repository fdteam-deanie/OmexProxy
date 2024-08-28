<?php

namespace App\ThirdApiClients\WhoIs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Connection implements Arrayable, Jsonable
{
    public string $domain;
    public string $org;
    public string $isp;

    public function __construct(string $domain, string $org, string $isp)
    {
        $this->domain = $domain;
        $this->org = $org;
        $this->isp = $isp;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getOrg(): string
    {
        return $this->org;
    }

    public function getIsp(): string
    {
        return $this->isp;
    }

    public function toArray(): array
    {
        return [
            'domain' => $this->getDomain(),
            'org' => $this->getOrg(),
            'isp' => $this->getIsp(),
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
