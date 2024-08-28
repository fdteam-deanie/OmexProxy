<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Proxy\ProxyDetailResource;
use App\Models\City;
use App\Models\ProxyISP;
use App\Models\State;
use App\Models\User;
use App\Models\ZIP;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProxyController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $count = $request->input('count', 25);
        $filters = $request->input('filters', []);

        $proxies =  $this->getPaginatedProxies($user, $filters, $count);

        $json = [
            'status' => 'success',
            'proxies' => $proxies,
        ];

        return response()->json($json);
    }

    private function getPaginatedProxies(User $user, $filters = false, $count = 25): LengthAwarePaginator
    {

        $proxiesBuilder = $user->proxies()->wherePivot('is_paid', true);

        if($filters) {

            if (!empty($filters['id'])) {
                $proxiesBuilder = $proxiesBuilder->where(['id' => $filters['id']]);
            }

            /* State */
            if (!empty($filters['state'])) {
                $states_ids = State::where('name', 'LIKE', '%'.$filters['state'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* City */
            if (!empty($filters['city'])) {
                $cities_ids = City::where('name', 'LIKE', '%'.$filters['city'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* ISP */
            if (!empty($filters['isp'])) {
                $isp_ids = ProxyISP::where('name', 'LIKE', '%'.$filters['isp'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* ZIP */
            if (!empty($filters['zip'])) {
                $zip_ids = ZIP::where('name', 'LIKE', '%'.$filters['zip'].'%')
                    ->pluck('id')
                    ->toArray();
            }

            /* Type */
            if (!empty($filters['type'])) {
                $type_ids = [$filters['type']];
            }

            if(!empty($filters['eup'])) {
                $is_used = (bool) $filters['eup'];
            }

            if(!empty($filters['ebp'])) {
                $is_blacklisted = (bool) $filters['ebp'];
            }

            if(!empty($filters['rop'])) {
                $is_residential = (bool) $filters['rop'];
            }

            if(!empty($filters['mp'])) {
                $is_mobile = (bool) $filters['mp'];
            }

            if(!empty($filters['sp'])) {
                $is_server = (bool) $filters['sp'];
            }


            if (!empty($filters['ip'])) {
                $proxiesBuilder = $proxiesBuilder->where('ip', 'LIKE', '%'.$filters['ip'].'%');
            }
            if (!empty($filters['domain'])) {
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
        }

        $paginated = $proxiesBuilder->orderBy('pivot_paid_at', 'desc')->paginate($count);
        $proxiesCollection = $paginated->getCollection();

        return $paginated->setCollection(
            ProxyDetailResource::collection($proxiesCollection)
                ->collection
        );

    }
}
