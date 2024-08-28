<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Http\Controllers\Api\ApiController;
use App\Models\ProxyRentPeriod;

class ProxyRentPeriodController extends ApiController
{
    public function index()
    {
        $rentPeriods = ProxyRentPeriod::all();
        return response()->json([
            "data" => $rentPeriods
        ]);
    }
}
