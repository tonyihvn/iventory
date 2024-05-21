<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\facilities;
use Faker\Generator as Faker;

$factory->define(facilities::class, function (Faker $faker) {
    return [
        'facility_name' => 'NA',
        'facility_no' => 'NA',
        'state' => 'NA',
        'lga' => 'NA',
        'town' => 'NA',
        'address' => 'NA',
        'phone_number' => 'NA',
        'contact_person' => 'NA',
    ];
});
