<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'code',
        'continent_id',
        'min_ping',
        'max_ping',
        'min_speed',
        'max_speed',
    ];

    public function continent(): BelongsTo
    {
        return $this->belongsTo(Continent::class, 'continent_id', 'id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }

    public function zips(): HasMany
    {
        return $this->hasMany(ZIP::class, 'country_id', 'id');
    }

    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class, 'country_id', 'id');
    }

    public function countryIps(): HasMany
    {
        return $this->hasMany(CountryIp::class, 'country_id', 'id');
    }

    public function ipProxies(): HasManyThrough
    {
        return $this->hasManyThrough(Proxy::class, CountryIp::class, 'country_id', 'country_ip_id', 'id', 'id');
    }
}
