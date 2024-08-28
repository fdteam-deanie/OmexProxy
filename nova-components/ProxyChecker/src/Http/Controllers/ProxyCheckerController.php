<?php
namespace Proxy\ProxyChecker\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Pivot\UserProxy;
use App\Models\Proxy;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;

class ProxyCheckerController extends Controller
{
    public function check(Request $request)
    {
        $complaint = Complaint::findOrFail($request->get('id'));
        $proxy = $complaint->proxy;

        $result = Http::get($proxy->ip_shown);
        return response()->json([
            'message' => 'success',
            'isOk' => $result->ok(),
        ]);
    }

    public function proxies(Complaint $complaint)
    {
        $proxy = $complaint->proxy;
        $proxies = Proxy::where('id', '!=', $proxy->id)
            ->where('type_id', $proxy->type_id)
            ->where('country_id', $proxy->country_id)
            ->get()
            ->map(function($proxy) {
                return [
                    'id' => $proxy->id,
                    'ip' => $proxy->ip_shown
                ];
            });

        return response()->json([
            'message' => 'success',
            'proxies' => $proxies,
        ]);
    }

    public function replaceProxy(Complaint $complaint, Request $request)
    {
        $proxy = $complaint->proxy;
        $user = $complaint->user;

        $userProxy = UserProxy::where('user_id', $user->id)
            ->where('proxy_id', $proxy->id)
            ->first();

        if(empty($userProxy)) {
            throw new \Exception('UserProxy not found');
        }

        $newProxy = Proxy::findOrFail($request->get('proxy_id'));

        $userProxy->proxy_id = $newProxy->id;
        $userProxy->save();

        $complaint->proxy_id = $newProxy->id;
        $complaint->save();

        return response()->json([
            'message' => 'success',
        ]);
    }
}
