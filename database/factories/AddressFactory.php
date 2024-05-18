<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerId = FakerFactory::create('id_ID');
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'name' => $fakerId->name,
            'phone' => '08' . $this->faker->regexify('[0-9]{10}'),
            'full_address' => $fakerId->address,
        ];
    }
}
