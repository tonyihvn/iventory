<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\inventory;
use Faker\Generator as Faker;

$factory->define(inventory::class, function (Faker $faker) {
    return [
        'item_name' => $faker->word,
        'description' => $faker->text,
        'serial_no' => $faker->word,
        //'category' => factory(App\category::class),
        'category' => $faker->randomElement(['Computers', 'Furnitures']),
        'type' => $faker->word,
        'date_purchased' => $faker->date(),
        'supplier' => $faker->word,
        'user_id' => rand(1,1),
        'quantity' => $faker->word,
        'status' => $faker->word,
        'department_id' => factory(App\department::class),
        'unit_id' => factory(App\unit::class),
        'facility_id' => factory(App\facilities::class),
        'added_by' => rand(1,1),
        'internal_location' => $faker->word,
        'remarks' => $faker->word,
    ];
});
