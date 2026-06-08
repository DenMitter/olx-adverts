<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\ChangedPriceNotificationMail;

class SendPriceChangedNotification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $subscription;
    protected $oldPrice;
    protected $currentPrice;
  
    public function __construct($subscription, $oldPrice, $currentPrice)
    {
        $this->subscription = $subscription;
        $this->oldPrice = $oldPrice;
        $this->currentPrice = $currentPrice;
    }

    public function handle(): void
    {
        Mail::to($this->subscription->email)->send(
            new ChangedPriceNotificationMail($this->subscription, $this->oldPrice, $this->currentPrice)
        );
    }
}
