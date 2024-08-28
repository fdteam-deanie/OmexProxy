<?php

namespace Proxy\SupportChat\Http\Resources;

use App\Models\Support\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketDetailResource extends JsonResource
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
            'subject' => $this->subject,
            'status' => $this->statusText,
            'image' => $this->image,
            'date' => $this->created_at->format('d.m.Y H:i'),
            'is_closed' => $this->status === Ticket::STATUS_CLOSED,
            'messages' => $this->messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'from' => $message->is_support ? 'Support Agent' : $message->user->name,
                    'is_me' => $message->user_id === auth()->guard('nova')->id(),
                    'is_image' => $message->is_image,
                    'date' => $message->created_at->format('d.m.Y'),
                    'time' => $message->created_at->format('H:i')
                ];
            })->groupBy('date'),
        ];
    }
}
