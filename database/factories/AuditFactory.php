<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\audit;
use Faker\Generator as Faker;

$factory->define(audit::class, function (Faker $faker) {
    return [
        'action' => $faker->word,
        'description' => $faker->text,
        'doneby' => $faker->word,
    ];
});
