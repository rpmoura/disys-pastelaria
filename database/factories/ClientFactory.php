<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'         => fake()->uuid(),
            'name'         => fake()->name(),
            'email'        => fake()->email(),
            'phone'        => fake()->e164PhoneNumber(),
            'birth_date'   => fake()->date(),
            'address'      => fake()->address(),
            'neighborhood' => fake()->colorName(),
            'add_on'       => fake()->sentence(2),
            'postcode'     => fake('pt_BR')->postcode(),
        ];
    }
}
