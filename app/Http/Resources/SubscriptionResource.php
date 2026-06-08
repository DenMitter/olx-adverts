<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AdvertisementResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'advertisement' => new AdvertisementResource($this->advertisement),
            'subscription_started_at' => $this->created_at->format('d.m.Y H:i'),
        ];
    }
}
