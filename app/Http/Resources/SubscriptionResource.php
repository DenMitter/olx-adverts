<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'olx_id' => $this->advertisement?->olx_id,
            'subscription_started_at' => $this->created_at->format('d.m.Y H:i'),
            'url' => $this->advertisement->url,
        ];
    }
}
