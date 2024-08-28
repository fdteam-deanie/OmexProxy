<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Proxy\ProxyDetailResource;
use App\Http\Resources\Proxy\ProxyResource;
use App\Models\Proxy;
use App\Models\ProxyType;
use Illuminate\Http\JsonResponse;

class ProxyController extends ApiController
{
    public function detail(Proxy $proxy)
    {
        return new ProxyResource($proxy);
    }

    public function getTypes(): JsonResponse
    {
        $types = ProxyType::orderBy('id', 'desc')->get();

        $types->transform(function (ProxyType $type) {
            return [
                'id' => $type->id,
                'name' => $type->name
            ];
        })
            ->toArray();

        return response()->json([
            'status' => 'success',
            'types' => $types
        ]);
    }
}
