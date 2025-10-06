<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\LevelSet>
 */
class LevelSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $legacyId = -1;
        $legacyId += 1;

        return [
            'legacy_id' => $legacyId,
            'name' => $this->faker->sentence(),
            'rounds' => 10,
            'author' => $this->faker->name(),
            'game_version' => 3,
            'image_url' => '',
            'description' => $this->faker->sentence(),
        ];
    }
}
