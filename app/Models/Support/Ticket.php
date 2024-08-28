<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasStatus;
class Ticket extends Model
{
  use HasStatus;
  
  public const STATUS_OPEN = 0;
  public const STATUS_IN_PROGRESS = 1;
  public const STATUS_CLOSED = 2;

  protected $fillable = [
    'subject',
    'status',
    'user_id',
    'closed_at',
    'image'
  ];

  protected $casts = [
    'closed_at' => 'datetime',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function messages(): HasMany
  {
    return $this->hasMany(TicketMessage::class);
  }

  public function getUnreadMessagesCountAttribute(): int
  {
    return $this->messages()->where('is_read', false)->count();
  }

  public function readMessages(): void
  {
    $this->messages()->update(['is_read' => true]);
  }
}
