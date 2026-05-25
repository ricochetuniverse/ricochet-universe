<?php

declare(strict_types=1);

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

#[UseModel(User::class)]
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '',
            'remember_token' => Str::random(10),

            'discord_id' => 1, // fixme
            'discord_avatar_url' => '', // fixme
            'is_admin' => false,
        ];
    }

    public function isAdmin(): Factory
    {
        return $this->state(function () {
            return [
                'is_admin' => true,
            ];
        });
    }
}
