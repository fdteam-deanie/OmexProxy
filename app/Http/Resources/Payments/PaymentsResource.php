<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->created_at->format('d.m.Y - H:i'),
            'type' => $this->is_deposit ? 'Deposit' : 'Payment',
            'amount' => $this->amount,
            'status' => $this->status ? 'Done' : 'Waiting',
        ];
    }
}
