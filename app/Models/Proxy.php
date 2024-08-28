<?php

namespace App\Models;

use App\ProxyProviders\BaseProxyProvider;
use App\ProxyProviders\ProxyProvider;
use App\ProxyProviders\ProxyProviderFactory;
use App\Services\ProxyHost\ProxyHostService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class Proxy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'continent_id',
        'country_id',
        'state_id',
        'city_id',
        'zip_id',
        'org_id',
        'isp_id',
        'type_id',
        'country_ip_id',
        'ip',
        'port',
        'ip_shown',
        'domain',
        'domain_shown',
        'price',
        'max_users_count',
        'is_used',
        'is_blacklisted',
        'is_residential',
        'is_mobile',
        'is_server',
        'is_online',
        'speed',
        'ping',
        'provider',
        'provider_proxy_id',
        'fraud_score'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_blacklisted' => 'boolean',
        'is_residential' => 'boolean',
        'is_mobile' => 'boolean',
        'is_server' => 'boolean',
        'is_online' => 'boolean',
    ];

    protected static function booted() {
        static::creating(function(Proxy $proxy) {
            if(empty($proxy->name)) {
                $proxy->generateName();
            }
            if(!empty($proxy->country_ip_id)) {
                $proxy->country()->associate($proxy->countryIp->country);
                $proxy->continent()->associate($proxy->countryIp->country->continent);
                $proxy->state()->associate($proxy->countryIp->state);
                $proxy->city()->associate($proxy->countryIp->city);
                $proxy->zip()->associate($proxy->countryIp->zip);
                $proxy->org()->associate($proxy->countryIp->org);
                $proxy->isp()->associate($proxy->countryIp->isp);
                $proxy->type()->associate($proxy->countryIp->type);

            }
        });

        static::created(function(Proxy $proxy) {
            $proxy->generateDisplayIp();
            $proxy->generateDisplayDomain();
        });

        static::deleting(function(Proxy $proxy)
        {
            if(!empty($proxy->container_id)) {
                ProxyHostService::make($proxy)->unhostProxy();
            }
        });
    }

    public function continent(): BelongsTo
    {
        return $this->belongsTo('App\Models\Continent', 'continent_id', 'id');
    }

	public function country(): BelongsTo
    {
		return $this->belongsTo('App\Models\Country', 'country_id', 'id');
	}

	public function city(): BelongsTo
    {
		return $this->belongsTo('App\Models\City', 'city_id', 'id');
	}

	public function type(): BelongsTo
    {
		return $this->belongsTo('App\Models\ProxyType', 'type_id', 'id');
	}

	public function org(): BelongsTo
    {
		return $this->belongsTo('App\Models\ProxyOrg', 'org_id', 'id');
	}

	public function isp(): BelongsTo
    {
		return $this->belongsTo('App\Models\ProxyISP', 'isp_id', 'id');
	}

	public function state(): BelongsTo
    {
		return $this->belongsTo('App\Models\State', 'state_id', 'id');
	}

	public function zip(): BelongsTo
    {
		return $this->belongsTo('App\Models\ZIP', 'zip_id', 'id');
	}

    public function countryIp(): BelongsTo
    {
        return $this->belongsTo('App\Models\CountryIp', 'country_ip_id', 'id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Order', 'user_proxies', 'proxy_id', 'order_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'user_proxies', 'proxy_id', 'user_id')
            ->using('App\Models\Pivot\UserProxy');
    }

    public function getOrdersCountAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getLastUser(): ?User
    {
        return $this->users()->orderBy('user_proxies.created_at', 'desc')->first();
    }

    public function generateName(): void
    {
        $this->attributes['name'] = uniqid("", true);
    }

    public function generateDisplayIp(): void
    {
        $this->ip = preg_replace(
            "/(\d+\.\d+)\.\d+\.\d+/",
            "$1.*.*",
            $this->ip_shown
        );
        $this->save();
    }

    public function generateDisplayDomain(): void
    {
        $this->domain = preg_replace(
            "/(?:[^.]+\.){0,2}([^.\s]+)\.([^.\s]+)\.([^.\s]+)/",
            "*.*.$2.$3",
            $this->domain_shown
        );
        $this->save();
    }

    public function getProvider(): ?ProxyProvider
    {
        if(empty($this->provider)) {
            return null;
        }
        return ProxyProviderFactory::make($this->provider);
    }

    public function getCredentials(User $user): ?array
    {
        $provider = $this->getProvider();
        if(empty($provider)) {
            return [
                'username' => $user->socks5_username,
                'password' => $user->socks5_password,
            ];
        }
        return $provider->getAuthCredentials($this, $user);
    }

    public function getConnectionHost(): string
    {
        $provider = $this->getProvider();
        if(empty($provider)) {
            return "$this->ip_shown:$this->port";
        }
        return $provider->getConnectionHost($this);
    }
}
