<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    const USD_ID = 1,
        BTC_ID = 2,
        LTC_ID = 3;

    const USD_CODE = 'usd',
        BTC_CODE = 'bitcoin',
        LTC_CODE = 'litecoin';

    protected $table = 'currencies';


}
