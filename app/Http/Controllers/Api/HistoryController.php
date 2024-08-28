<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\History\GetProxiesRequest;
use App\Http\Resources\History\ProxyResource;
use App\Models\User;
use App\Services\HistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends ApiController
{
    protected ?User $user;
    protected HistoryService $historyService;

    public function boot(): void
    {
        $this->user = Auth::user();
        $this->historyService = new HistoryService($this->user);
    }

    public function getProxies(GetProxiesRequest $request): JsonResponse
    {

        $paginated = $this->historyService->preparedCollection($request)->getPaginated();
        $proxies = $paginated->setCollection(
            ProxyResource::collection($paginated->getCollection())
                ->collection
        );

        return response()->json([
            'status'=>'success',
            'proxies' => $proxies
        ]);
    }
}
