<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\category;
use Faker\Generator as Faker;

$factory->define(category::class, function (Faker $faker) {
    return [
        'category_name' => 'NA',
        'description' => 'NA',

        // 'category_name' => '$faker->randomElement(['Computers', 'Furnitures'])',
        // 'description' => '$faker->text',
    ];
});
