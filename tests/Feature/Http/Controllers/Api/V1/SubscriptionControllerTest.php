<?php

use App\Models\Subscription;

test('subscriptions list', function () {
    $subscription = Subscription::factory()->create();

    $this->getJson('/api/v1/subscriptions')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.olx_id', $subscription->advertisement->olx_id);
});
