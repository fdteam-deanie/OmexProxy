<?php

namespace App\Services\Support;

use App\Http\Requests\Support\StoreTicketRequest;
use App\Mail\MFA\SendAuthCodeMail;
use App\Mail\Support\SendSupportReply;
use App\Models\Support\Ticket;
use App\Models\Support\TicketMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TicketService
{
    protected User $user;

    public function __construct(?User $user)
    {
        if(!$user) {
            $user = auth()->user();
        }
         $this->user = $user;
    }

    public function createTicket(StoreTicketRequest $request)
    {
        $file = $request->file('image');
        if($file && !empty($file) && $file->isValid()) {
            $uniqueName = uniqid() . '.' . $request->image->extension();
            $imageUrl = $request->file('image')->storeAs('tickets', $uniqueName , 'public');;
        }

        $ticket = $this->user->tickets()->create([
            'subject' => $request->subject,
            'status' => Ticket::STATUS_OPEN,
            'image' =>  $imageUrl ?? null,
        ]);

        $this->newMessage($ticket, $request->message);

        if(!empty($imageUrl)) {
            $this->newImageMessage($ticket, $imageUrl);
        }

        return $ticket;
    }

    public function newMessage(Ticket $ticket, string $body, bool $isSupport = false)
    {
        return $ticket->messages()->create([
            'body' => $body,
            'is_support' => $isSupport,
            'user_id' => $this->user->id,
            'is_read' => $this->user->id === $ticket->user_id,
        ]);
    }

    public function newImageMessage(Ticket $ticket, string $url)
    {
        return $ticket->messages()->create([
            'body' => $url,
            'is_support' => false,
            'is_image' => true,
            'user_id' => $this->user->id,
            'is_read' => $this->user->id === $ticket->user_id,
        ]);
    }

    public function reply(Ticket $ticket, string $body)
    {
        $message = $this->newMessage($ticket, $body, true);
        Mail::to($ticket->user->email)->send(new SendSupportReply($ticket));
        return $message;
    }

    public function closeTicket(Ticket $ticket)
    {
        $ticket->update([
            'status' => Ticket::STATUS_CLOSED,
            'closed_at' => now(),
        ]);
    }


}
