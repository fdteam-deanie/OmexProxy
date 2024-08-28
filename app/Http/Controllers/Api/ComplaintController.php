<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreComplaintRequest;
use App\Http\Resources\History\ProxyResource;
use App\Models\Complaint;
use App\Models\Proxy;
use App\Models\User;
use App\Services\ComplaintService;
use App\Services\HistoryService;
use Illuminate\Http\Request;

class ComplaintController extends ApiController
{
    protected User $user;
    protected ComplaintService $complaintService;

    public function boot()
    {
        $this->user = auth()->user();
        $this->complaintService = new ComplaintService($this->user);
    }

    public function store(StoreComplaintRequest $request)
    {
        $proxy = Proxy::find($request->proxy_id);

        if(empty($proxy)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proxy not found.'
            ]);
        }

        $complaint = $this->complaintService->createComplaint($proxy, $request->message);

        return response()->json([
            'status' => 'success',
            'message' => 'Complaint sent successfully. Thank you for your feedback. Your ticket number #' . $complaint->id . '.',
            'complaint' => $complaint
        ]);
    }
}
