<?php

namespace App\Http\Resources\Proxy;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Vite;

class ProxyResource extends JsonResource
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
            'ip' => $this->isIpShowed() ? $this->ip_shown : $this->ip,
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
                'ping' => $this->ping ?? null,
                'speed' => $this->speed ?? null,
                'dns' => null,
                'usage' => $this->users()->count(),
            ],
            'price' => $this->price,
            'connection' => $this->getConnectionInfo(),
            'isPurchased' => $this->isPurchased(),
            'short_location' => $this->getLocation(),
        ];
    }

    private function isIpShowed(): bool
    {
        /** @var Request $request */
        $request = request();
        $routeName = $request->route()->getName();

        /** @var User $user */
        $user = $request->user();
        $isOwner = $this->users->contains($user->id);

        return ($routeName == 'proxy.detail' && $isOwner);
    }

    private function getConnectionInfo(): ?string
    {
        $proxy = request()->user()->proxies()->where('proxy_id', $this->id)->first();

        if(!$proxy) {
            return null;
        }

        return $proxy->pivot->getConnectionInfo();
    }

    private function isPurchased(): bool
    {
        $proxy = request()->user()->proxies()->where('proxy_id', $this->id)->first();

        if(!empty($proxy) && $proxy->pivot->is_paid) {
            return true;
        }

        return false;
    }

    public function getLocation(): string
    {
        $location = '';
        if($this->country) {
            $location .= $this->country->code;
        }
        if($this->state) {
            $location .= '/' . $this->state->name;
        }
        if($this->city) {
            $location .= '-' . $this->city->name;
        }
        if($this->zip) {
            $location .= ' ' . $this->zip->name;
        }
        return $location;
    }
}

