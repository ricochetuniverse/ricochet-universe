<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Mod;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(Mod::class)]
class ModFactory extends Factory
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
            'description' => $this->faker->text(),
            'video_embed_source' => 'https://www.youtube.com/embed/xxxxxxxxxxx',
            'download_link' => $this->faker->url(),
            'trigger_codename' => $this->faker->word(),
        ];
    }
}
