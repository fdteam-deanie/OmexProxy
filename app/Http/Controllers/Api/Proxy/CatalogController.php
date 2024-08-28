<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ExcludeTrait;
use App\Http\Resources\Proxy\ProxyResource;
use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Proxy;
use App\Models\ProxyISP;
use App\Models\State;
use App\Models\User;
use App\Models\ZIP;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends ApiController
{
    use ExcludeTrait;

    public function index(Request $request): JsonResponse
    {

        /** @var User $user */
        $user = Auth::user();
        $countryIds = [];

        $continentId = $request->input('continent_id');
        $countryId = $request->input('country_id');

        if(!is_null($countryId)) {
            $countryIds = [$countryId];
        } else {
            $countryIds = Country::where([ 'continent_id' => $continentId ])->pluck('id')->toArray();
        }

        $count = $request->input('count');
        $filters = $request->input('filters');
        $excludedProxyIds = $this->getExcludeProxyIds($user);

        $proxies = $this->getPaginatedProxies($countryIds, $excludedProxyIds, $filters, $count);

        $json = [
            'status' => 'success',
            'proxies' => $proxies,
        ];

        return response()->json($json);
    }

    private function getPaginatedProxies($countryIds = [], $excludedProxyIds = [], $filters = false, $count = 25): LengthAwarePaginator
    {
        $proxiesBuilder = Proxy::withCount('users')
            ->having('max_users_count', '>', \DB::raw('users_count'));

        if(count($countryIds) > 0) {
            $proxiesBuilder = $proxiesBuilder->whereIn('country_id',  $countryIds);
        }

        if (count($excludedProxyIds) > 0) {
            $proxiesBuilder = $proxiesBuilder->whereNotIn('id', $excludedProxyIds);
        }

        if($filters) {

            /* State */
            if ($filters['state']) {
                $states_ids = State::where('name', 'LIKE', '%'.$filters['state'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* City */
            if ($filters['city']) {
                $cities_ids = City::where('name', 'LIKE', '%'.$filters['city'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* ISP */
            if ($filters['isp']) {
                $isp_ids = ProxyISP::where('name', 'LIKE', '%'.$filters['isp'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* ZIP */
            if ($filters['zip']) {
                $zip_ids = ZIP::where('name', 'LIKE', '%'.$filters['zip'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* Type */
            if ($filters['type']) {
                $type_ids = [$filters['type']];
            }

            if($filters['eup']) {
                $is_used = (bool) $filters['eup'];
            }

            if($filters['ebp']) {
                $is_blacklisted = (bool) $filters['ebp'];
            }

            if($filters['rop']) {
                $is_residential = (bool) $filters['rop'];
            }

            if($filters['mp']) {
                $is_mobile = (bool) $filters['mp'];
            }

            if($filters['sp']) {
                $is_server = (bool) $filters['sp'];
            }


            if ($filters['ip']) {
                $proxiesBuilder = $proxiesBuilder->where('ip', 'LIKE', '%'.$filters['ip'].'%');
            }
            if ($filters['domain']) {
                $proxiesBuilder = $proxiesBuilder->where('domain', 'LIKE', '%'.$filters['domain'].'%');
            }
            if (isset($states_ids)) {
                $proxiesBuilder = $proxiesBuilder->whereIn('state_id', $states_ids);
            }
            if (isset($cities_ids)) {
                $proxiesBuilder = $proxiesBuilder->whereIn('city_id', $cities_ids);
            }
            if (isset($isp_ids)) {
                $proxiesBuilder = $proxiesBuilder->whereIn('isp_id', $isp_ids);
            }
            if (isset($zip_ids)) {
                $proxiesBuilder = $proxiesBuilder->whereIn('zip_id', $zip_ids);
            }
            if (isset($type_ids)) {
                $proxiesBuilder = $proxiesBuilder->whereIn('type_id', $type_ids);
            }

            /* Flag filters */
            if (isset($is_used) && $is_used) {
                $proxiesBuilder = $proxiesBuilder->where('is_used', '<>', $is_used);
            }
            if (isset($is_blacklisted) && $is_blacklisted) {
                $proxiesBuilder = $proxiesBuilder->where('is_blacklisted', '<>', $is_blacklisted);
            }
            if (isset($is_residential) && $is_residential) {
                $proxiesBuilder = $proxiesBuilder->where('is_residential', $is_residential);
            }
            if (isset($is_mobile) && $is_mobile) {
                $proxiesBuilder = $proxiesBuilder->where('is_mobile', $is_mobile);
            }
            if (isset($is_server) && $is_server) {
                $proxiesBuilder = $proxiesBuilder->where('is_server', $is_server);
            }

            if ($filters['added']) {

                switch ($filters['added']) {
                    case 'today': {
                        $proxiesBuilder = $proxiesBuilder->where('created_at', '>=', Carbon::today()); break;
                    }
                    case '3': {
                        $proxiesBuilder = $proxiesBuilder->where('created_at', '>=', Carbon::today()->subDays(3)); break;
                    }
                    case 'week': {
                        $proxiesBuilder = $proxiesBuilder->where('created_at', '>=', Carbon::today()->subDays(7)); break;
                    }
                    case 'month': {
                        $proxiesBuilder = $proxiesBuilder->where('created_at', '>=', Carbon::today()->subDays(31)); break;
                    }
                }
            }

            if ($filters['price']) {
                $proxiesBuilder = $proxiesBuilder->where('price', '<=', $filters['price']);
            }

        }

        if (Auth::check()) {
            $user = Auth::user();
            $userProxiesIds = $user->proxies()->wherePivot('is_paid', true)->get()->pluck('id')->toArray();
            $proxiesBuilder = $proxiesBuilder->whereNotIn('id', $userProxiesIds);
        }

        $paginated = $proxiesBuilder->paginate($count);

        return $paginated->setCollection(
            ProxyResource::collection($paginated->getCollection())
                ->collection
        );
    }
}
