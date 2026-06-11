<?php

use App\Models\Advertisement;
use App\Models\Subscription;
use App\Services\OlxService;
use App\Jobs\SendPriceChangedNotification;
use Illuminate\Support\Facades\Queue;

test('it updates price and dispatches job when price changes', function () {
    Queue::fake();

    $advertisement = Advertisement::factory()->create([
        'title' => 'Тестове оголошення OLX',
        'last_price' => 5000
    ]);
    
    $subscription = Subscription::factory()->create([
        'advertisement_id' => $advertisement->id,
        'status' => 'active'
    ]);

    $this->mock(OlxService::class, function ($mock) use ($advertisement) {
        $mock->shouldReceive('getPrice')
             ->with($advertisement->olx_id)
             ->andReturn(4500.0);
    });

    $this->artisan('app:check-price-changes')
         ->expectsOutput('Send 1 mails')
         ->assertSuccessful();

    expect($advertisement->refresh()->last_price)->toEqual(4500);

    Queue::assertPushed(SendPriceChangedNotification::class, function ($job) use ($subscription) {
        return $job->getSubscription()->id === $subscription->id;
    });
});

test('it does nothing when price remains the same', function () {
    Queue::fake();

    $advertisement = Advertisement::factory()->create([
        'title' => 'Тестове оголошення OLX',
        'last_price' => 5000
    ]);
    Subscription::factory()->create(['advertisement_id' => $advertisement->id, 'status' => 'active']);

    $this->mock(OlxService::class, function ($mock) use ($advertisement) {
        $mock->shouldReceive('getPrice')->andReturn(5000.0);
    });

    $this->artisan('app:check-price-changes')
         ->expectsOutput('Send 0 mails')
         ->assertSuccessful();

    Queue::assertNotPushed(SendPriceChangedNotification::class);
});

test('it skips advertisement if price cannot be fetched', function () {
    Queue::fake();

    $advertisement = Advertisement::factory()->create([
        'title' => 'Тестове оголошення OLX',
        'last_price' => 5000
    ]);
    Subscription::factory()->create(['advertisement_id' => $advertisement->id, 'status' => 'active']);

    $this->mock(OlxService::class, function ($mock) {
        $mock->shouldReceive('getPrice')->andReturn(null);
    });

    $this->artisan('app:check-price-changes')
         ->expectsOutput('Send 0 mails')
         ->assertSuccessful();

    expect($advertisement->refresh()->last_price)->toEqual(5000);
    Queue::assertNotPushed(SendPriceChangedNotification::class);
});