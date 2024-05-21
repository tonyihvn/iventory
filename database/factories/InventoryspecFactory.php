<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\inventoryspec;
use Faker\Generator as Faker;

$factory->define(inventoryspec::class, function (Faker $faker) {
    return [
        'property' => $faker->word,
        'value' => $faker->word,
        'itemid' => $faker->randomNumber(),
        'inventory_id' => factory(App\inventory::class),
    ];
});
