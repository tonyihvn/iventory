<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\department;
use Faker\Generator as Faker;

$factory->define(department::class, function (Faker $faker) {
    return [
        'department_name' => 'NA',
        'facility' => 'NA',
        'internal_location' => 'NA',
        'description' => 'NA',
    ];
});
