<?php

namespace App\Http\Resources\History;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Vite;

class ProxyResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'is_online' => $this->is_online,
            'is_paid' => $this->pivot->is_paid,
            'paid_at' => $this->pivot->paid_at->format('d.m.Y H:i'),
            'expired_at' => $this->pivot->expired_at->format('d.m.Y H:i'),
            'ip_shown' => $this->ip_shown,
            'location' => $this->getLocation(),
            'flag' => $this->getFlag(),
            'isp' => $this->isp->name,
            'type' => $this->type->name,
            'price' => $this->price
        ];
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

    public function getFlag()
    {
        $fileName = $this->country->code ? strtolower($this->country->code) : 'undefined';

        return Vite::asset("resources/images/flags/{$fileName}.png");
    }
}
