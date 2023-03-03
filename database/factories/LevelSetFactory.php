<?php

namespace Database\Factories;

use App\LevelSet;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelSetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LevelSet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
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
            'description' => '',
        ];
    }
}
