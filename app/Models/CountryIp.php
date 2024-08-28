<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CountryIp extends Model
{
    protected $fillable = ['country_id', 'ip', 'username', 'password'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo('App\Models\State', 'state_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }

    public function zip(): BelongsTo
    {
        return $this->belongsTo('App\Models\ZIP', 'zip_id', 'id');
    }

    public function isp(): BelongsTo
    {
        return $this->belongsTo('App\Models\ProxyISP', 'isp_id', 'id');
    }

    public function org(): BelongsTo
    {
        return $this->belongsTo('App\Models\ProxyOrg', 'org_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo('App\Models\ProxyType', 'type_id', 'id');
    }

    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class);
    }
}
