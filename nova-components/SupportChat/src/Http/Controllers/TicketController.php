<?php

namespace Proxy\SupportChat\Http\Controllers;

use App\Http\Requests\Support\StoreTicketMessageRequest;
use Proxy\SupportChat\Http\Resources\TicketDetailResource;
use App\Models\Support\Ticket;
use App\Services\Support\TicketService;

class TicketController
{
    public function show(Ticket $ticket)
    {
        $ticket->load('messages');

        return new TicketDetailResource($ticket);
    }

    public function messages(Ticket $ticket)
    {
        $ticket->load('messages');

        return response()->json([
            'data' => $ticket->messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'from' => $message->is_support ? 'Support Agent' : $message->user->name,
                    'is_me' => $message->user_id === auth()->guard('nova')->id(),
                    'is_image' => $message->is_image,
                    'date' => $message->created_at->format('d.m.Y'),
                    'time' => $message->created_at->format('H:i')
                ];
            })->groupBy('date')
        ]);
    }

    public function reply(StoreTicketMessageRequest $request, Ticket $ticket)
    {
        $service = new TicketService(auth()->guard('nova')->user());
        $message = $service->reply($ticket, $request->message);

        $ticket->status = Ticket::STATUS_IN_PROGRESS;
        $ticket->save();

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }

    public function close(Ticket $ticket)
    {
        $service = new TicketService(auth()->guard('nova')->user());
        $service->closeTicket($ticket);

        return response()->json([
            'message' => 'Ticket closed successfully'
        ]);
    }
}
