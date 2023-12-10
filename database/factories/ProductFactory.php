<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Fixture\ImageFixture;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'  => fake()->uuid(),
            'name'  => fake()->name(),
            'photo' => 'products/default.png',
            'price' => fake()->numerify('###.##'),
        ];
    }

    public function imageBase64Encoded(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'photo' => ImageFixture::getImageBase64Encoded(),
            ];
        });
    }
}
