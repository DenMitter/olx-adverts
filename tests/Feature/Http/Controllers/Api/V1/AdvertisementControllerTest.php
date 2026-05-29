<?php

use App\Models\Advertisement;

test('advertisements list', function () {
    $advertisement = Advertisement::factory()->create();

    $this->getJson('/api/v1/advertisements')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.last_price', $advertisement->last_price);
});
