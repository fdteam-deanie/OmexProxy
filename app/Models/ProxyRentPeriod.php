<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProxyRentPeriod extends Model
{
    protected $fillable = [
        'name',
        'days'
    ];
}
