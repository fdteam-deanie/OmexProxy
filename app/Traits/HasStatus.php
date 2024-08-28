<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasStatus
{
  public function getStatusTextAttribute(): string
  {
      switch ($this->status) {
          case self::STATUS_OPEN:
              return 'Open';
          case self::STATUS_IN_PROGRESS:
              return 'In Progress';
          case self::STATUS_CLOSED:
              return 'Closed';
          default:
              return 'Unknown';
      }
  }
}
