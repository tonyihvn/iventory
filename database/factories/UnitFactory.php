<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\unit;
use Faker\Generator as Faker;

$factory->define(unit::class, function (Faker $faker) {
    return [
        'unit_name' => 'NA',
        'department' => 'NA',
        'facility' => 'NA',
        'internal_location' => 'NA',
        'description' => 'NA',
    ];
});
