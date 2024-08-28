<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Support\StoreTicketMessageRequest;
use App\Models\Support\Ticket;
use App\Models\User;
use App\Services\Support\TicketService;

class TicketMessageController extends ApiController
{
    protected ?User $user;
    protected TicketService $ticketService;

    public function boot()
    {
        $this->user = auth()->user();
        $this->ticketService = new TicketService($this->user);
    }

    public function index(Ticket $ticket)
    {
        abort_if(!$this->user || $ticket->user_id !== $this->user->id, 403);

        $messages = $ticket->messages;
        $ticket->readMessages();

        return response()->json([
            'data' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'from' => $message->is_support ? 'Support Agent' : $message->user->name,
                    'is_me' => $message->user_id === auth()->id(),
                    'is_image' => $message->is_image,
                    'date' => $message->created_at->format('d.m.Y'),
                    'time' => $message->created_at->format('H:i')
                ];
            })->groupBy('date')
        ]);
    }

    public function store(StoreTicketMessageRequest $request, Ticket $ticket)
    {
        abort_if(!$this->user || $ticket->user_id !== $this->user->id || $ticket->status == Ticket::STATUS_CLOSED, 403);

        $message = $this->ticketService->newMessage($ticket, $request->message);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }
}
