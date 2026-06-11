<?php

use App\Models\Advertisement;
use Carbon\Carbon;

test('it returns a paginated list of advertisements in correct resource format', function () {
    $fixedDate = Carbon::create(2026, 6, 8, 20, 0, 0);

    $advertisement = Advertisement::factory()->create([
        'olx_id' => 123456789,
        'title' => 'Продам BMW X5',
        'url' => 'https://olx.ua/bmw',
        'last_price' => 15000,
        'currency' => 'USD',
        'last_checked_at' => $fixedDate,
    ]);

    $response = $this->getJson('/api/v1/advertisements');
    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'olx_id' => 123456789,
                'title' => 'Продам BMW X5',
                'url' => 'https://olx.ua/bmw',
                'last_price' => 15000,
                'currency' => 'USD',
                'last_checked_at' => '08.06.2026 20:00',
            ]
        ]
    ]);

    $response->assertJsonStructure([
        'data',
        'links' => ['first', 'last', 'prev', 'next'],
        'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total']
    ]);
});