<?php

namespace Database\Factories;

use App\Models\CreditPack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CreditPack>
 */
class CreditPackFactory extends Factory
{
    protected $model = CreditPack::class;

    public function definition(): array
    {
        return [
            'code' => 'pack_'.fake()->unique()->numberBetween(1, 1000),
            'name' => 'Pack '.fake()->numberBetween(1, 100).' créditos',
            'credits_amount' => fake()->numberBetween(1, 100),
            'price_amount' => fake()->numberBetween(1000, 10000),
            'currency' => 'ARS',
            'description' => fake()->sentence(),
            'is_active' => true,
            'sort_order' => 0,
            'metadata' => null,
        ];
    }
}
