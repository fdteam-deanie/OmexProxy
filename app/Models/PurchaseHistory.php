<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PurchaseHistory extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'orders';


  protected $fillable = [
    'user_id',
    'amount',
    'status',
];

  public function user(): BelongsTo
  {
      return $this->belongsTo(User::class);
  }
}
