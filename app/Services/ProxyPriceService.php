<?php

namespace App\Services;

use App\Models\Proxy;
use App\Models\Rate;

class ProxyPriceService
{

    public function updateProxyPrice(Proxy $proxy): void
    {
        $liveTime = $proxy->created_at->diffInDays(now());
        $rate = Rate::where('days', '>=', $liveTime)->orderBy('days', 'asc')->first();

        if(empty($rate)) {
            //set proxy is new and some another logic
            return;
        }

        $this->updatePriceByRate($proxy, $rate);
    }
    /**
     * @param  Proxy  $proxy
     * @param  Rate  $rate
     * @return void
     */
    public function updatePriceByRate(Proxy $proxy, ?Rate $rate): void
    {
        $priceField = $this->getPriceFieldByProxy($proxy);
        if(empty($priceField)) return;

        $proxy->price = $rate->$priceField;
        $proxy->save();
    }

    /**
     * @param  Proxy  $proxy
     * @return string|null
     */
    public function getPriceFieldByProxy(Proxy $proxy): ?string
    {
        if($proxy->is_residential) {
            return 'residential_price';
        } else if($proxy->is_mobile) {
            return 'mobile_price';
        } else if($proxy->is_server) {
            return 'server_price';
        }
        return null;
    }
}
