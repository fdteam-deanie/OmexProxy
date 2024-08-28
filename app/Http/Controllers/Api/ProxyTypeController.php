<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Proxy\ProxyTypeResource;
use App\Models\ProxyType;
use Illuminate\Http\Request;

class ProxyTypeController extends ApiController
{

    public function index()
    {
        $types = ProxyType::orderBy('id', 'desc')->get();
        return ProxyTypeResource::collection($types);
    }
}
