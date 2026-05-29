<?php

namespace Database\Factories;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'advertisement_id' => Advertisement::factory(),
            'email' => fake()->safeEmail(),
            'status' => 'pending',
        ];
    }
}
