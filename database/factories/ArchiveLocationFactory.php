<?php

namespace Database\Factories;

use App\Models\ArchiveLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArchiveLocation>
 */
class ArchiveLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([ArchiveLocation::TYPE_PHYSICAL, ArchiveLocation::TYPE_VIRTUAL]);

        return [
            'name' => strtoupper(fake()->unique()->word()).' '.fake()->numberBetween(1, 99),
            'description' => fake()->sentence(),
            'location' => $type === ArchiveLocation::TYPE_PHYSICAL ? fake()->address() : '192.168.1.'.fake()->numberBetween(10, 250).' / NAS',
            'type' => $type,
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the location is physical.
     */
    public function physical(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ArchiveLocation::TYPE_PHYSICAL,
            'location' => fake()->address(),
        ]);
    }

    /**
     * Indicate that the location is virtual.
     */
    public function virtual(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ArchiveLocation::TYPE_VIRTUAL,
            'location' => '192.168.1.100 / Serveur DGB',
        ]);
    }
}
