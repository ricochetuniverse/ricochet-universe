<?php

declare(strict_types=1);

namespace Database\Factories;

use App\LevelSetTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LevelSetTag>
 */
class LevelSetTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}
