<?php

declare(strict_types=1);

namespace Database\Factories;

use App\LevelSetTag;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(LevelSetTag::class)]
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
            'count_visible' => 0,
        ];
    }
}
