<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendPriceChangedNotification;

#[Signature('app:check-price-changes')]
#[Description('Check for price changes in advertisements and notify subscribers')]
class CheckPriceChanges extends Command
{
    protected $count = 0;
    
    public function handle(OlxService $olxService)
    {
        $advertisements = Advertisement::whereHas('subscriptions', function ($query) {
            $query->where('status', 'active');
        })->get();

        foreach ($advertisements as $advertisement) {
            $currentPrice = $olxService->getPrice($advertisement->olx_id);

            if (!$currentPrice) {
                continue;
            }

            if ((float)$advertisement->last_price !== $currentPrice) {
                $oldPrice = $advertisement->last_price;
                $subscriptions = $advertisement->subscriptions()->where('status', 'active')->get();

                foreach ($subscriptions as $subscription) {
                    SendPriceChangedNotification::dispatch($subscription, $oldPrice, $currentPrice);
                    $this->count++;
                }

                $advertisement->update([
                    'last_price' => $currentPrice,
                ]);
            }
        }

        $this->info('Send ' . $this->count . ' mails');
    }
}
