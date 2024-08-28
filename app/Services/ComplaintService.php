<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Proxy;
use App\Models\User;

class ComplaintService
{
    protected User $user;

    public function __construct(?User $user)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
    }

    public function createComplaint(Proxy $proxy, ?string $message = null): Complaint
    {
        $complaint = new Complaint();
        $complaint->user_id = $this->user->id;
        $complaint->proxy_id = $proxy->id;
        $complaint->message = $message;
        $complaint->save();

        return $complaint;
    }
}
