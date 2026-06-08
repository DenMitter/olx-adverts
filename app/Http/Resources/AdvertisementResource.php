<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'olx_id' => $this->olx_id,
            'title' => $this->title,
            'url' => $this->url,
            'last_price' => $this->last_price,
            'currency' => $this->currency,
            'last_checked_at' => $this->last_checked_at?->format('d.m.Y H:i'),
        ];
    }
}
