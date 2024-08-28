<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Support\StoreTicketRequest;
use App\Http\Resources\Support\TicketDetailResource;
use App\Http\Resources\Support\TicketResource;
use App\Models\Support\Ticket;
use App\Models\User;
use App\Services\Support\TicketService;

class TicketController extends ApiController
{
    protected ?User $user;
    protected TicketService $ticketService;
    public function boot()
    {
        $this->user = auth()->user();
        $this->ticketService = new TicketService($this->user);
    }
    public function index()
    {
        $tickets = $this->user->tickets()->latest()->get();

        return TicketResource::collection($tickets);
    }

    public function show(Ticket $ticket)
    {
        abort_if(!$this->user || $ticket->user_id !== $this->user->id, 403);

        $ticket->load('messages');
        $ticket->readMessages();

        return new TicketDetailResource($ticket);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketService->createTicket($request);

        return new TicketResource($ticket);
    }
}
