<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Advertisement;
use App\Http\Requests\Api\V1\StoreSubscriptionRequest;
use App\Jobs\SendVerificationEmailJob;
use App\Services\SubscriptionService;
use Exception;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();

        try {
            $subscription = $this->subscriptionService->makeSubscription($data);

            if ($subscription->wasRecentlyCreated === false) {
                return (new SubscriptionResource($subscription))
                    ->response()
                    ->setStatusCode(200);
            }

            return (new SubscriptionResource($subscription))
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function confirm($subscription)
    {
        $subscription = Subscription::findOrFail($subscription);
        $subscription = tap($subscription)->update(['status' => 'active']);

        return (new SubscriptionResource($subscription))
            ->response()
            ->setStatusCode(200);
    }
}
