<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    const PENDING = 0;
    const SUCCESS = 1;
    const FAILED = 2;

    protected $fillable = ['user_id', 'amount', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}
