<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'olx_id' => fake()->unique()->bothify('??###'),
            'url' => 'https://www.olx.ua/d/obyavlenie/test-ID'.fake()->bothify('???????').'.html',
            'last_price' => fake()->optional()->numberBetween(1000, 100000),
        ];
    }
}
