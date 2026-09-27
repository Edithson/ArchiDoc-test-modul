<?php

namespace Database\Factories;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personnel>
 */
class PersonnelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'matricule' => 'MAT-'.fake()->unique()->numberBetween(1000, 9999),
            'phone' => '+237 '.fake()->numberBetween(650000000, 699999999),
            'address' => fake()->address(),
        ];
    }
}
