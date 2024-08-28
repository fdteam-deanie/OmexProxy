<?php

namespace App\ThirdApiClients\WhoIs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Response implements Arrayable, Jsonable
{
    public string $ip;
    public Continent $continent;
    public Country $country;
    public Region $region;
    public string $city;
    public string $zip;
    public Connection $connection;

    public function __construct(
        string $ip,
        Continent $continent,
        Country $country,
        Region $region,
        string $city,
        string $zip,
        Connection $connection
    ) {
        $this->ip = $ip;
        $this->continent = $continent;
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        $this->zip = $zip;
        $this->connection = $connection;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getContinent(): Continent
    {
        return $this->continent;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function toArray(): array
    {
        return [
            'ip' => $this->ip,
            'continent' => $this->continent->toArray(),
            'country' => $this->country->toArray(),
            'region' => $this->region->toArray(),
            'city' => $this->city,
            'zip' => $this->zip,
            'connection' => $this->connection->toArray(),
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
