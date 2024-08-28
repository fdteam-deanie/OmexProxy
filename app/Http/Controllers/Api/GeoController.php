<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ExcludeTrait;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Proxy;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;

class GeoController extends ApiController
{
    use ExcludeTrait;

    public function getContinents(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = [];

        $continentsList = Continent::orderBy('id', 'asc')->get();

        if ($continentsList->count() > 0) {

            $excludedProxyIds = $this->getExcludeProxyIds($user);

            foreach ($continentsList as $continent) {

                $countries_id = [];
                $countries = Country::where(['continent_id' => $continent->id])->get();
                foreach ($countries as $country) {
                    $countries_id[] = $country->id;
                }

                $proxies_count = Proxy::whereIn('country_id', $countries_id)->whereNotIn('id', $excludedProxyIds)->count();

                $data[] = [
                    'id' => $continent->id,
                    'name' => $continent->name,
                    'proxies_cnt' => $proxies_count];

            }
        }

        return response()->json([
            'status'=> 'success',
            'continents' => $data
        ]);
    }

    public function getCountries(?int $continentId = null): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = [];

        $countriesList = (is_null($continentId)
            ? Country::orderBy('name', 'asc')
            : Country::where(['continent_id' => $continentId])->orderBy('name', 'asc'))
            ->get();

        if ($countriesList->count() > 0) {
            $excludedProxyIds = $this->getExcludeProxyIds($user);

            foreach ($countriesList as $country) {
                $fileName = $country->code ? strtolower($country->code) : 'undefined';

                $data[] = [

                    'id' => $country->id,
                    'continent_id' => $country->continent_id,
                    'flag' => Vite::asset("resources/images/flags/{$fileName}.png"),
                    'name' => $country->name,
                    'proxies_cnt' => Proxy::where(['country_id' => $country->id])->whereNotIn('id', $excludedProxyIds)->count(),
                ];
            }
        }

        return response()->json([
            'status'=> 'success',
            'countries' => $data
        ]);

    }
}
