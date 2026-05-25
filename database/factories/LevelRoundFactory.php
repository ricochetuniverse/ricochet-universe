<?php

declare(strict_types=1);

namespace Database\Factories;

use App\LevelRound;
use App\LevelSet;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(LevelRound::class)]
class LevelRoundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'author' => $this->faker->name(),
            'note1' => $this->faker->sentence(),
            'note2' => $this->faker->sentence(),
            'note3' => $this->faker->sentence(),
            'note4' => $this->faker->sentence(),
            'note5' => $this->faker->sentence(),
            'source' => '',
            'image_file_name' => '',
            'round_number' => $this->faker->numberBetween(1, 10),
            'level_set_id' => LevelSet::factory(),
        ];
    }
}
