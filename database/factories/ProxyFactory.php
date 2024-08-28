<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Proxy;
use App\Models\ProxyISP;
use App\Models\ProxyOrg;
use App\Models\ProxyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proxy>
 */
class ProxyFactory extends Factory
{
    protected $model = Proxy::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ip_shown = $this->faker->ipv4;
        $ip = preg_replace(
            "/(\d+\.\d+)\.\d+\.\d+/",
            "$1.*.*",
            $ip_shown
        );

        $domain_shown = $this->faker->userName . '.' . $this->faker->domainName;
        $domain = preg_replace(
            "/(?:[^.]+\.){0,2}([^.\s]+)\.([^.\s]+)\.([^.\s]+)/",
            "*.*.$2.$3",
            $domain_shown
        );

        /** @var Country $country */
        $country = Country::inRandomOrder()->first();
        $city = $country->cities()->inRandomOrder()->first();
        $state = $country->states()->inRandomOrder()->first();
        $zip = $country->zips()->inRandomOrder()->first();

        return [
            'ip' => $ip,
            'ip_shown' => $ip_shown,
            'domain' => $domain,
            'domain_shown' => $domain_shown,
            'price' => $this->faker->randomFloat(2, 0, 10),
            'users_count' => env('MAX_PROXY_USERS_COUNT'),
            'is_used' => $this->faker->randomElement([ 0, 1 ]),
            'is_blacklisted' => $this->faker->randomElement([ 0, 1 ]),
            'is_residential' => $this->faker->randomElement([ 0, 1 ]),
            'is_mobile' => $this->faker->randomElement([ 0, 1 ]),
            'is_server' => $this->faker->randomElement([ 0, 1 ]),
            'country_id' => $country->id,
            'continent_id' => $country->continent->id,
            'state_id' => $state->id ?? null,
            'city_id' => $city->id ?? null,
            'zip_id' => $zip->id ?? null,
            'isp_id' => ProxyISP::where('active', true)->inRandomOrder()->first()->id,
            'org_id' => ProxyOrg::where('active', true)->inRandomOrder()->first()->id,
            'type_id' => ProxyType::where('active', true)->inRandomOrder()->first()->id
        ];
    }
}
