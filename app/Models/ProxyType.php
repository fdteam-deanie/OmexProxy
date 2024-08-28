<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProxyType extends Model
{
    use HasFactory;

    const PROXY_TYPE_STATIC_ID = 1;
    const PROXY_TYPE_STATIC_NAME = 'Static';
    const PROXY_TYPE_ROTATION_ID = 2;
    const PROXY_TYPE_ROTATION_NAME = 'Rotation';

    protected $table = 'proxy_types';

    protected $fillable = [
        'name',
        'active',
    ];
    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class, 'type_id', 'id');
    }
}
