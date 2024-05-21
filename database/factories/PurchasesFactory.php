<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\purchases;
use Faker\Generator as Faker;

$factory->define(purchases::class, function (Faker $faker) {
    return [
        'itemid' => $faker->word,
        'quantity' => $faker->word,
        'costper' => $faker->word,
        'supplier' => $faker->word,
        'date_purchased' => $faker->date(),
        'invoice_no' => $faker->word,
        'description' => $faker->text,
        'facility' => $faker->word,
        'internal_location' => $faker->word,
        'remarks' => $faker->word,
    ];
});
