<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\supplier;
use Faker\Generator as Faker;

$factory->define(supplier::class, function (Faker $faker) {
    return [
        'supplier_name' => 'NA',
        'company_name' => 'NA',
        'phone_number' => 'NA',
        'email' => 'NA',
        'items' => 'NA',
        'category' => 'NA',
    ];
});
