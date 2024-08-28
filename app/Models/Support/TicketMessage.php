<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    protected $fillable = [
        'body',
        'ticket_id',
        'user_id',
        'is_support',
        'is_image',
        'is_read'
    ];

    protected $casts = [
        'is_support' => 'boolean',
        'is_image' => 'boolean',
        'is_read' => 'boolean'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
