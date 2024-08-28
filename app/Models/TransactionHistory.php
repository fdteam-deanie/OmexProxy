<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TransactionHistory extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'payments';


  protected $fillable = [
    'user_id',
    'order_id',
    'is_deposit',
    'is_bonus',
    'amount',
    'status',
];

  public function user(): BelongsTo
  {
      return $this->belongsTo(User::class);
  }
}
