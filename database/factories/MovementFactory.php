<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\movement;
use Faker\Generator as Faker;

$factory->define(movement::class, function (Faker $faker) {
    return [
        'inventories_id' => rand(1,10),
        'from' => $faker->word,
        'to' => $faker->word,
        'from_user' => $faker->word,
        'to_user' => $faker->word,
        'reason' => $faker->word,
        'user_id' => rand(1,10),
        'date_moved' => $faker->date(),
    ];
});
