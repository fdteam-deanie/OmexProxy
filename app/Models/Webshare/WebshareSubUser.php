<?php

namespace App\Models\Webshare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebshareSubUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id'
    ];
}
