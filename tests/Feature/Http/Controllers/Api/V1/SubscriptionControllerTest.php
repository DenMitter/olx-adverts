<?php

use App\Models\Subscription;

test('check subscriptions store & deduplication', function () {
    $link = 'https://www.olx.ua/d/uk/obyavlenie/prodam-termnovo-parketnik-dzhip-4h4-ID10A6Bc.html?search_reason=search%7Corganic';
    $this->postJson('/api/v1/subscriptions', ['email' => 'test1@olx.com', 'url' => $link]);
    $this->postJson('/api/v1/subscriptions', ['email' => 'test2@olx.com', 'url' => $link]);

    $this->assertDatabaseCount('subscriptions', 2);
    $this->assertDatabaseCount('advertisements', 1);
});

test('it returns 200 and does not duplicate when same user subscribes twice', function () {
    $link = 'https://www.olx.ua/d/uk/obyavlenie/prodam-termnovo-parketnik-dzhip-4h4-ID10A6Bc.html?search_reason=search%7Corganic';
    
    $response1 = $this->postJson('/api/v1/subscriptions', ['email' => 'test1@olx.com', 'url' => $link]);
    $response1->assertStatus(201);

    $response2 = $this->postJson('/api/v1/subscriptions', ['email' => 'test1@olx.com', 'url' => $link]);
    $response2->assertStatus(200);

    $this->assertDatabaseCount('subscriptions', 1);
});

test('check subscription confirm', function () {
    $link = 'https://www.olx.ua/d/uk/obyavlenie/prodam-termnovo-parketnik-dzhip-4h4-ID10A6Bc.html?search_reason=search%7Corganic';
    $this->postJson('/api/v1/subscriptions', ['email' => 'test@olx.com', 'url' => $link]);

    $subscription = Subscription::where('email', 'test@olx.com')->first();
    $verificationUrl = URL::temporarySignedRoute('subscriptions.confirm', now()->addHours(24), ['subscription' => $subscription->id]);
    expect($subscription->status)->toEqual('pending');

    $response = $this->get($verificationUrl);
    $response->assertOk();
    expect($subscription->refresh()->status)->toEqual('active');
});