<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PropertyAnalytics;
use Faker\Generator as Faker;

$factory->define(PropertyAnalytics::class, function (Faker $faker) {
    return [
        'property_id' => $faker->randomNumber(4),
        'analytic_type_id' => $faker->randomNumber(2),
        'value' => $faker->numberBetween($min = 1, $max = 1500),
    ];
});
