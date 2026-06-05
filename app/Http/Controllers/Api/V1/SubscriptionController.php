<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Advertisement;
use App\Http\Requests\Api\V1\StoreSubscriptionRequest;
use App\Jobs\SendVerificationEmailJob;

class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();

        $advertId = Advertisement::extractOlxId($data['url']);

        $advertisement = Advertisement::firstOrCreate(
            ['olx_id' => $advertId], 
            ['url' => $data['url']]
        );

        $subscription = $advertisement->subscriptions()->firstOrCreate(
            ['email' => $data['email']]
        );

        if ($subscription->status === 'active') {
            return response()->json(['message' => 'You are already have subscripe for this advertisement']);
        }

        // check if the user has been verified before
        $ifVerified = Subscription::query()
            ->where('email', $data['email'])
            ->where('status', 'active')
            ->exists();

        if ($ifVerified) {
            $subscription->update(['status' => 'active']);
        } else {
            SendVerificationEmailJob::dispatch($subscription);
        }

        return (new SubscriptionResource($subscription))
            ->response()
            ->setStatusCode(201);
    }

    public function confirm($subscription)
    {
        $subscription = Subscription::findOrFail($subscription);
        $subscription = tap($subscription)->update(['status' => 'active']);

        return (new SUbscriptionResource($subscription))
            ->response()
            ->setStatusCode(200);
    }
}
