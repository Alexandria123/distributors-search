<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distributor>
 */
class DistributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'emails' => json_encode([$this->faker->email]),
            'domains' => json_encode([$this->faker->domainName]),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'status' => 'Дистрибьютор'
        ];
    }
}
