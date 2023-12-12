<?php

namespace Database\Factories;

use App\Models\{Client, Order};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::query()->firstOrCreate(['id' => 1], Client::factory()->make()->toArray());

        return [
            'client_id' => $client->id,
            'total'     => (float)fake()->numerify('##.##'),
        ];
    }
}
