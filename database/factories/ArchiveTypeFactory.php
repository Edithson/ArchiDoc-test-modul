<?php

namespace Database\Factories;

use App\Models\ArchiveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArchiveType>
 */
class ArchiveTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => strtoupper(fake()->unique()->word()),
            'description' => fake()->sentence(),
            'dua' => fake()->numberBetween(1, 99),
            'created_by' => User::factory(),
        ];
    }
}
