<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'name',
        'mobile_price',
        'residential_price',
        'server_price',
        'days'
    ];
}
