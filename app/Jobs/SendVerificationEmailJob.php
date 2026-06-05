<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionVerificationMail;

class SendVerificationEmailJob implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        $verificationUrl = URL::temporarySignedRoute('subscriptions.confirm', now()->addHours(24), ['subscription' => $this->subscription->id]);
        Mail::to($this->subscription->email)->send(new SubscriptionVerificationMail($verificationUrl));
    }
}
