<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Continent extends Model
{
    use HasFactory;

    protected $table = 'continents';

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class, 'continent_id', 'id');
    }

    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class, 'continent_id', 'id');
    }
}
