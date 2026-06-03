<?php

test('check subscriptions store & deduplication', function () {
    $link = 'https://www.olx.ua/d/uk/obyavlenie/prodam-termnovo-parketnik-dzhip-4h4-ID10A6Bc.html?search_reason=search%7Corganic';
    
    $this->postJson('/api/v1/subscriptions', ['email' => 'test1@olx.com', 'url' => $link]);
    $this->postJson('/api/v1/subscriptions', ['email' => 'test2@olx.com', 'url' => $link]);

    $this->assertDatabaseCount('subscriptions', 2);
    $this->assertDatabaseCount('advertisements', 1);
});
