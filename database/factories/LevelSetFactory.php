<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LevelSet;
use Faker\Generator as Faker;

$factory->define(LevelSet::class, function (Faker $faker) {
    static $legacyId = -1;
    $legacyId += 1;

    return [
        'legacy_id' => $legacyId,
        'name' => $faker->sentence,
        'rounds' => 10,
        'author' => $faker->name,
        'game_version' => 3,
        'image_url' => '',
        'description' => '',
    ];
});
