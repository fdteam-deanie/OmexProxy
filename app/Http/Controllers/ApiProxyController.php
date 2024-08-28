<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Proxy\ProxyResource;
use App\Models\Cart;
use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Proxy;
use App\Models\ProxyISP;
use App\Models\ProxyType;
use App\Models\State;
use App\Models\User;
use App\Models\ZIP;
use App\Services\BalanceService;
use App\Services\CartService;
use App\Services\UnlimitedSubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;

/**
 * @deprecated
 */
class ApiProxyController extends ApiController
{
    protected User $user;
    protected UnlimitedSubscriptionService $unlimitedSubscriptionService;
    protected CartService $cartService;
    protected BalanceService $balanceService;

    public function boot()
    {
        $this->user = Auth::user();
        $this->unlimitedSubscriptionService = new UnlimitedSubscriptionService($this->user);
        $this->cartService = new CartService($this->user);
        $this->balanceService = new BalanceService($this->user);
    }

    /** @deprecated  */
	public function getContinents() {

		$list = Continent::orderBy('id', 'asc')->get();
		if (!$list) {
			return [];
		}

		$cart = Cart::getCart($this->user->id);
		$exclude = [];

		if ($cart->proxies->isNotEmpty()) {
			foreach ($cart->proxies as $proxy) {
				$exclude[] = $proxy->id;
			}
		}

		$userProxyIds = $this->user->proxies()->wherePivot('is_paid', true)->get()->pluck('id')->toArray();
		$exclude = array_merge($exclude, $userProxyIds);

		$data = [];
		foreach ($list as $rec) {

			$countries_id = [];
			$countries = Country::where(['continent_id' => $rec->id])->get();
			foreach ($countries as $c) {
				$countries_id[] = $c->id;
			}

			$proxies_count = Proxy::whereIn('country_id', $countries_id)->whereNotIn('id', $exclude)->count();

			$data[] = ['id' => $rec->id, 'name' => $rec->name, 'count' => $proxies_count];

		}

		return $data;

	}

    /** @deprecated  */

	public function getCountries($continentId = false)
    {
		if (!$continentId) {
			$list = Country::orderBy('name', 'asc')->get();
		}
		else {
			$list = Country::where(['continent_id' => $continentId])->orderBy('name', 'asc')->get();
		}

		if (!$list) {
			return [];
		}

		$cart = Cart::getCart($this->user->id);
		$exclude = [];

		if ($cart->proxies->isNotEmpty()) {
			foreach ($cart->proxies as $item) {
				$exclude[] = $item->id;
			}
		}

        $userProxyIds = $this->user->proxies()->wherePivot('is_paid', true)->get()->pluck('id')->toArray();
        $exclude = array_merge($exclude, $userProxyIds);

		$data = [];
		foreach ($list as $rec) {

            $fileName = $rec->code ? strtolower($rec->code) : 'undefined';

			$data[] = [

				'id' => $rec->id,
				'continent_id' => $rec->continent_id,
                'flag' => Vite::asset("resources/images/flags/{$fileName}.png"),
				'name' => $rec->name,
				'count' => Proxy::where(['country_id' => $rec->id])->whereNotIn('id', $exclude)->count(),

			];
		}

		return $data;

	}

    /** @deprecated  */
	public function getUserProxies(User $user, $count = 10, $filters = false) {

		$ids = $user->proxies()->wherePivot('is_paid', true)->get()->pluck('id')->toArray();

		if (!$filters) {
			$list = Proxy::whereIn('id', $ids)->orderBy('id', 'desc')->limit($count)->get();
		}
		else {

			$proxy_ids = [];

			/* State */
			if ($filters['state']) {

				$states_ids = [];
				$states = State::where('name', 'LIKE', '%'.$filters['state'].'%')->get();
				if ($states->count()) {
					foreach ($states as $sr) {
						$states_ids[] = $sr->id;
					}
				}

			}
			/* City */
			if ($filters['city']) {

				$cities_ids = [];
				$cities = City::where('name', 'LIKE', '%'.$filters['city'].'%')->get();
				if ($cities->count()) {
					foreach ($cities as $cr) {
						$cities_ids[] = $cr->id;
					}
				}

			}

			/* ISP */
			if ($filters['isp']) {

				$isp_ids = [];
				$isps = ProxyISP::where('name', 'LIKE', '%'.$filters['isp'].'%')->get();
				if ($isps->count()) {
					foreach ($isps as $is) {
						$isp_ids[] = $is->id;
					}
				}

			}

			/* ZIP */
			if ($filters['zip']) {

				$zip_ids = [];
				$zips = ZIP::where('name', 'LIKE', '%'.$filters['zip'].'%')->get();
				if ($zips->count()) {
					foreach ($zips as $zr) {
						$zip_ids[] = $zr->id;
					}
				}

			}

			$proxy_ids = [];

			/* */
			$proxies = Proxy::get();

			if ($filters['ip']) {
				$proxies = Proxy::where('ip', 'LIKE', '%'.$filters['ip'].'%');
			}
			if ($filters['domain']) {
				$proxies = Proxy::where('domain', 'LIKE', '%'.$filters['domain'].'%');
			}
			if (isset($states_ids)) {
				$proxies = $proxies->whereIn('state_id', $states_ids);
			}
			if (isset($cities_ids)) {
				$proxies = $proxies->whereIn('city_id', $cities_ids);
			}
			if (isset($isp_ids)) {
				$proxies = $proxies->whereIn('isp_id', $isp_ids);
			}
			if (isset($zip_ids)) {
				$proxies = $proxies->whereIn('zip_id', $zip_ids);
			}

			if ($filters['ip'] or $filters['domain']) {
				$proxies = $proxies->get();
			}

			foreach ($proxies as $pr) {
				$proxy_ids[] = $pr->id;
			}

			$list = Proxy::whereIn('id', $proxy_ids)->orderBy('id', 'desc')->get();

		}

		if (!$list->count()) {
			return [];
		}

		$data = [];

		foreach ($list as $rec) {
            $fileName = $rec->country->code ? strtolower($rec->country->code) : 'undefined';

			$data[] = [

				'id' => $rec->id,
				'location' => [

					'country' => [

						'id' => $rec->country_id,
						'name' => $rec->country->name,
                        'flag' => Vite::asset("resources/images/flags/{$fileName}.png")

					],
					'city' => [

						'id' => $rec->city->id ?? null,
						'name' => $rec->city->name ?? null,

					],

					'state' => ($rec->state ? $rec->state->name : null),
					'zip' => ($rec->zip ? $rec->zip->name : null),
					'ip' => $rec->ip_shown,
					'port' => $rec->port,
					'domain' => $rec->domain_shown,
					'org' => $rec->org->name,
					'isp' => $rec->isp->name,

				],
				'blacklist' => ['status' => 'clear'],
				'info' => [

					'added' => 'Today',
					'type' => $rec->type->name,
					'ping' => null,
					'speed' => null,
					'dns' => null,
					'usage' => null,

				],
				'price' => $rec->price,

			];

		}

		return $data;

	}

    /** @deprecated */
	public function getProxies($countryId = false, $count = 10, $filters = false, $exclude = false) {

		if (!$filters) {

			if (!$countryId) {

				if (!$exclude) {
					$list = Proxy::orderBy('id', 'desc')->limit($count)->get();

				}
				else {
					$list = Proxy::whereNotIn('id', $exclude)->orderBy('id', 'desc')->limit($count)->get();
				}

			}
			else {

				if (!$exclude) {
					$list = Proxy::where(['country_id' => $countryId])->orderBy('id', 'desc')->limit($count)->get();
				}
				else {
					$list = Proxy::where(['country_id' => $countryId])->whereNotIn('id', $exclude)->orderBy('id', 'desc')->limit($count)->get();
				}

			}

		}
		else {

			$proxy_ids = [];

			/* State */
			if ($filters['state']) {

				$states_ids = [];
				$states = State::where('name', 'LIKE', '%'.$filters['state'].'%')->get();
				if ($states->count()) {
					foreach ($states as $sr) {
						$states_ids[] = $sr->id;
					}
				}

			}
			/* City */
			if ($filters['city']) {

				$cities_ids = [];
				$cities = City::where('name', 'LIKE', '%'.$filters['city'].'%')->get();
				if ($cities->count()) {
					foreach ($cities as $cr) {
						$cities_ids[] = $cr->id;
					}
				}

			}

			/* ISP */
			if ($filters['isp']) {

				$isp_ids = [];
				$isps = ProxyISP::where('name', 'LIKE', '%'.$filters['isp'].'%')->get();
				if ($isps->count()) {
					foreach ($isps as $is) {
						$isp_ids[] = $is->id;
					}
				}

			}

			/* ZIP */
			if ($filters['zip']) {

				$zip_ids = [];
				$zips = ZIP::where('name', 'LIKE', '%'.$filters['zip'].'%')->get();
				if ($zips->count()) {
					foreach ($zips as $zr) {
						$zip_ids[] = $zr->id;
					}
				}

			}

			/* Type */
			if ($filters['type']) {
				$type_ids = [$filters['type']];
			}

            if($filters['eup']) {
                $is_used = $filters['eup'];
            }

            if($filters['ebp']) {
                $is_blacklisted = $filters['ebp'];
            }

            if($filters['rop']) {
                $is_residential = $filters['rop'];
            }

            if($filters['mp']) {
                $is_mobile = $filters['mp'];
            }

            if($filters['sp']) {
                $is_server = $filters['sp'];
            }

			$proxy_ids = [];

			/* @var Proxy[] $proxies */
			$proxies = Proxy::get();

			if ($filters['ip']) {
				$proxies = Proxy::where('ip', 'LIKE', '%'.$filters['ip'].'%');
			}
			if ($filters['domain']) {
				$proxies = Proxy::where('domain', 'LIKE', '%'.$filters['domain'].'%');
			}
			if (isset($states_ids)) {
				$proxies = $proxies->whereIn('state_id', $states_ids);
			}
			if (isset($cities_ids)) {
				$proxies = $proxies->whereIn('city_id', $cities_ids);
			}
			if (isset($isp_ids)) {
				$proxies = $proxies->whereIn('isp_id', $isp_ids);
			}
			if (isset($zip_ids)) {
				$proxies = $proxies->whereIn('zip_id', $zip_ids);
			}
			if (isset($type_ids)) {
				$proxies = $proxies->whereIn('type_id', $type_ids);
			}

            /* Flag filters */
            if (isset($is_used) && $is_used) {
                $proxies = $proxies->where('is_used', '<>', $is_used);
            }
            if (isset($is_blacklisted) && $is_blacklisted) {
                $proxies = $proxies->where('is_blacklisted', '<>', $is_blacklisted);
            }
            if (isset($is_residential) && $is_residential) {
                $proxies = $proxies->where('is_residential', $is_residential);
            }
            if (isset($is_mobile) && $is_mobile) {
                $proxies = $proxies->where('is_mobile', $is_mobile);
            }
            if (isset($is_server) && $is_server) {
                $proxies = $proxies->where('is_server', $is_server);
            }

			if ($filters['added']) {

				if ($filters['added'] == 'today') {
					$proxies = $proxies->where('created_at', '>=', \Carbon\Carbon::today());
				}
				elseif ($filters['added'] == '3') {
					$proxies = $proxies->where('created_at', '>=', \Carbon\Carbon::today()->subDays(3));
				}
				elseif ($filters['added'] == 'week') {
					$proxies = $proxies->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7));
				}
				elseif ($filters['added'] == 'month') {
					$proxies = $proxies->where('created_at', '>=', \Carbon\Carbon::today()->subDays(31));
				}
			}

			if ($filters['price']) {
				$proxies = $proxies->where('price', '<=', $filters['price']);
			}

			if ($filters['ip'] or $filters['domain']) {
				$proxies = $proxies->get();
			}

			foreach ($proxies as $pr) {
				$proxy_ids[] = $pr->id;
			}

			$list = Proxy::whereIn('id', $proxy_ids)->orderBy('id', 'desc')->get();

		}

		if (!$list->count()) {
			return [];
		}

		$data = [];

		/* UserProxies */
		$userProxiesIds = [];

		if (Auth::check()) {
            $user = Auth::user();
            $userProxiesIds = $user->proxies()->wherePivot('is_paid', true)->get()->pluck('id')->toArray();
		}

		foreach ($list as $rec) {

			if (in_array($rec->id, $userProxiesIds)) {
				continue;
			}

            $fileName = $rec->country->code ? strtolower($rec->country->code) : 'undefined';

            $data[] = [

				'id' => $rec->id,
				'location' => [

					'country' => [

						'id' => $rec->country_id,
						'name' => $rec->country->name,
                        'flag' => Vite::asset("resources/images/flags/{$fileName}.png")
					],
					'city' => [

						'id' => $rec->city->id ?? null,
						'name' => $rec->city->name ?? null,

					],

					'state' => ($rec->state ? $rec->state->name : null),
					'zip' => ($rec->zip ? $rec->zip->name : null),
					'ip' => $rec->ip,
					'domain' => $rec->domain,
					'org' => $rec->org->name,
					'isp' => $rec->isp->name,

				],
				'blacklist' => ['status' => 'clear'],
				'info' => [

					'added' => date('d.m.Y', strtotime($rec->created_at)),
					'type' => $rec->type->name,
					'ping' => null,
					'speed' => null,
					'dns' => null,
					'usage' => null,

				],
				'price' => $rec->price,

			];

		}

		return $data;

	}

    /** @deprecated */
	public function catalog(Request $request) {

		/* Selected & Filters */
		$continentId = $request->continentId;
		if (!$continentId) {
			$continentId = 4;
		}
		$countryId = $request->countryId;
		$filters = $request->filters;
		$count = $request->count;

		if ($filters) {
			$filters = $request->all();
		}

		$cart = Cart::getCart($this->user->id);
		$exclude = [];

		if ($cart->proxies->isNotEmpty()) {
			foreach ($cart->proxies as $item) {
				$exclude[] = $item->id;
			}
		}

		$types = ProxyType::orderBy('id', 'desc')->get();
		$types_ok = [];
		if ($types->count()) {
			foreach ($types as $t) {
				$types_ok[] = ['id' => $t->id, 'name' => $t->name];
			}
		}

		$result = [

//			'continents' => self::getContinents(),
//			'countries' => self::getCountries($continentId),
			'proxies' => self::getProxies($countryId, $count, $filters, $exclude),
//			'types' => $types_ok,

		];

		$json = [

			'status' => 'success',
			'result' => $result,

		];

		return response()->json($json);

	}

    /** @deprecated  */
	public function my_catalog(Request $request) {

        $user = Auth::user();
		/* Selected & Filters */
		$filters = $request->filters;
		$count = $request->count;

		if ($filters) {
			$filters = $request->all();
		}

		$result = [

			'proxies' => self::getUserProxies($user, $count, $filters),
			'types' => ProxyType::orderBy('id', 'desc')->get(),

		];

		$json = [

			'status' => 'success',
			'result' => $result,

		];

		return response()->json($json, 200);

	}

    /**
     * @deprecated
     */
	public function order(Request $request): \Illuminate\Http\JsonResponse
    {
        $orderedProxies = [];
        /* @var User $user */
        $user = Auth::user();

        if (empty($user->socks5_username) || empty($user->socks5_password)) {
            return response()->json(['status' => 'error', 'message' => 'Please, sets up socks5 credentials!'], 442);
        }


		Cart::init($user->id);
		$cart = Self::getCart(true);

		if (!$cart or !isset($cart->cartItems)) {
			return response()->json(['status' => 'error', 'message' => 'Invalid card'], 442);
		}


        if($this->unlimitedSubscriptionService->isUserHasActiveSubscription()) {
            $this->unlimitedSubscriptionService->attachProxiesFromCart(
                $this->unlimitedSubscriptionService->getUserActiveSubscription(),
                $this->cartService->getUserCart()
            );

            $cart = Cart::getCart();

            return response()->json([
                'status' => 'success',
                'message' => 'Success! Order confirmed!',
                'cartItems' => $cart->items,
                'cartTotal' => $cart->total,
            ]);
        }



		/* Check user's balance */
		if ($this->balanceService->getUserBalance() < $cart->cartTotal) {
			return response()->json(['status' => 'error', 'message' => 'Insufficient funds!'], 442);
		}

		/* Create Order */
		$order_data = [

			'user_id' => $user->id,
			'amount' => $cart->cartTotal,
			'status' => 1,

		];

		$order = new Order;
		$order->fill($order_data);
		$order->save();

		/* Create Payment */
		$payment_data = [

			'user_id' => $user->id,
			'order_id' => $order->id,
			'is_deposit' => false,
			'amount' => $cart->cartTotal,
			'status' => 1,

		];

		$payment = new Payment;
		$payment->fill($payment_data);
		$payment->save();

		foreach ($cart->cartItems as $proxy) {

            $user->proxies()->attach($proxy->id, [
                'is_paid' => true,
                'order_id' => $order->id,
                'expired_at' => now()->addDay(),
            ]);
            $orderedProxies[] = $proxy->id;
		}
        $orderedProxies = Proxy::find($orderedProxies);

		Cart::init($user->id);
		Cart::clean();
		$cart = Cart::getCart();

		$data = [

			'status' => 'success',
			'message' => 'Success! Order confirmed!',
			'cartItems' => $cart->items,
			'cartTotal' => $cart->total,
            'orderedProxies' => ProxyResource::collection($orderedProxies)->resolve()

		];

		return response()->json($data, 200);

	}

    /** @deprecated */
	public function my_payments(Request $request) {

		$payments = Payment::where(['user_id' => Auth::user()->id])->orderBy('id', 'desc')->get();
		$data = [];

		if ($payments->count()) {
			foreach ($payments as $rec) {

				$this_type = 'Deposit';
				if (!$rec->is_deposit) {
					$this_type = 'Payment';
				}

				$this_date = date('d.m.Y - H:i', strtotime($rec->created_at));
				$this_status = 'Waiting';
				if ($rec->status == 1) {
					$this_status = 'Done';
				}

				$data[] = [

					'id' => $rec->id,
					'date' => $this_date,
					'type' => $this_type,
					'amount' => $rec->amount,
					'status' => $this_status,

				];

			}
		}

		return response()->json(['status' => 'ok', 'payments' => $data], 200);

	}

}
