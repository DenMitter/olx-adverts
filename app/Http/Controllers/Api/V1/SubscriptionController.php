<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Advertisement;
use App\Http\Requests\Api\V1\StoreSubscriptionRequest;

class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();

        preg_match('#-ID([A-Za-z0-9]+)\.html#', parse_url($data['url'], PHP_URL_PATH), $matches);
        $advertId = $matches[1];

        $advertisement = Advertisement::firstOrCreate(
            ['olx_id' => $advertId], 
            ['url' => $data['url']]
        );

        $subscription = $advertisement->subscriptions()->firstOrCreate(
            ['email' => $data['email']]
        );

        return (new SubscriptionResource($subscription))
            ->response()
            ->setStatusCode(201);
    }
}
