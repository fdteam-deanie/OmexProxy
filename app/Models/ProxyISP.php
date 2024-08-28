<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProxyISP extends Model
{
    use HasFactory;

	protected $table = 'proxy_isp';

    protected $fillable = [
        'name',
        'active',
    ];
    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class, 'isp_id', 'id');
    }
}
