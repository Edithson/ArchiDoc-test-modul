<?php

namespace Database\Factories;

use App\Models\Piece;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Piece>
 */
class PieceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'obligatory' => fake()->boolean(70),
        ];
    }
}
