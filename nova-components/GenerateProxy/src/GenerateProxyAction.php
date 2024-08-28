<?php

namespace Proxy\GenerateProxy;

use App\Http\Controllers\Controller;
use App\Models\CountryIp;
use App\Models\Proxy;
use App\Models\ProxyType;
use App\Models\Rate;
use App\Services\ProxyHost\ProxyHostApiService;
use App\Services\ProxyHost\ProxyHostService;
use App\Services\ProxyHost\ProxyHostWebhooksService;
use App\Services\ProxyPriceService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class GenerateProxyAction extends Controller
{

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $host = CountryIp::findOrFail($request->id);
        $type = $host->type ?? ProxyType::findByName('ISP');

        $service = new ProxyHostWebhooksService();
        $ports = $service->getFreePorts($host);

        $usedPorts = $host->proxies()->whereIn('port', $ports)->get()->pluck('port')->toArray();

        $newPorts = array_diff($ports, $usedPorts);

        foreach ($newPorts as $port) {
            $this->createProxy($host, $port, $type);
        }

        return response()->json([
            'message' => "Proxies generated successfully! Handled".count($ports)." ports. New proxies count: " . count($newPorts)
        ]);
    }

    private function createProxy(CountryIp $host, int $port, ProxyType $type): Proxy
    {
        $speed = rand($host->speed * 0.7, $host->speed * 1.3);
        $ping = rand($host->ping * 0.7, $host->ping * 1.3);

        $proxy = new Proxy();

        $proxy->generateName();

        $proxy->ip_shown = $host->ip;
        $proxy->port = $port;
        $proxy->domain_shown = $host->domain;
        $proxy->price = 0;

        $proxy->is_mobile = $type->name == 'MOB';
        $proxy->is_residential = $type->name == 'DCH';
        $proxy->is_server = $type->name == 'ISP' || $type->name == 'ISP/MOB';

        $proxy->speed = $speed;
        $proxy->ping = $ping;

        $proxy->max_users_count = env('MAX_PROXY_USERS_COUNT');

        $proxy->countryIp()->associate($host);

        $rate = Rate::where('days', '>=', 0)->orderBy('days', 'asc')->first();

        (new ProxyPriceService())->updatePriceByRate($proxy, $rate);

        return $proxy;
    }
}
