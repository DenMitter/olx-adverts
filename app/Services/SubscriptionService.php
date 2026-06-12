<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Subscription;
use App\Jobs\SendVerificationEmailJob;
use Exception;

class SubscriptionService
{
    protected $olxService;

    public function __construct(OlxService $olxService)
    {
        $this->olxService = $olxService;
    }

    public function makeSubscription(array $data): Subscription
    {
        $email = $data['email'];
        $url = $data['url'];

        // search advert ot parsing
        $advertisement = Advertisement::where('url', $url)->first();

        if (!$advertisement) {
            $olxData = $this->olxService->fetchPrice($url);

            if (!$olxData) {
                throw new Exception('Unable to fetch advertisement data from OLX.');
            }

            $advertisement = Advertisement::firstOrCreate(
                ['olx_id' => $olxData['olx_id']],
                [
                    'url'        => $url,
                    'title'      => $olxData['title'],
                    'last_price' => $olxData['price'],
                    'currency'   => $olxData['currency'],
                ]
            );
        }

        // create subscription
        $subscription = $advertisement->subscriptions()->firstOrCreate(
            ['email' => $email]
        );

        if ($subscription->status === 'active') {
            return $subscription;
        }

        // processing email verification
        $isVerifiedBefore = Subscription::where('email', $email)
            ->where('status', 'active')
            ->exists();

        if ($isVerifiedBefore) {
            $subscription->update(['status' => 'active']);
        } else {
            SendVerificationEmailJob::dispatch($subscription);
        }

        return $subscription;
    }
}