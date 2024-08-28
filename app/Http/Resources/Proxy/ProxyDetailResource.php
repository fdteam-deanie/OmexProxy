<?php

namespace App\Http\Resources\Proxy;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Vite;

class ProxyDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fileName = $this->country->code ? strtolower($this->country->code) : 'undefined';

        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'ip_shown' => $this->ip_shown,
            'port' => $this->port,
            'location' => [
                'continent' => [
                    'id' => $this->country->continent->id,
                    'name' => $this->country->continent->name,
                ],
                'country' => [
                    'id' => $this->country->id,
                    'name' => $this->country->name,
                    'flag' => Vite::asset("resources/images/flags/{$fileName}.png")
                ],
                'city' => [
                    'id' => $this->city->id ?? null,
                    'name' => $this->city->name ?? null,
                ],
                'state' => $this->state->name ?? null,
                'zip' => $this->zip->name ?? null,
                'ip' => $this->ip,
                'domain' => $this->domain ?? null,
                'org' => $this->org->name ?? null,
                'isp' => $this->isp->name ?? null,
            ],
            'blacklist' => ['status' => 'clear'],
            'info' => [
                'added' => date('d.m.Y', strtotime($this->created_at)),
                'type' => $this->type->name,
                'is_static' => (bool) $this->is_static,
                'ping' => $this->ping ?? null,
                'speed' => $this->speed ?? null,
                'dns' => null,
                'usage' => $this->users()->count(),
                'paid_at' => $this->pivot->paid_at->format('m.d.Y H:i:s') ?? null,
                'expired_at' => $this->pivot->expired_at->format('d.m.Y H:i') ?? null
            ],
            'connection' => $this->pivot->getConnectionInfo(),
            'price' => $this->price
        ];
    }
}

