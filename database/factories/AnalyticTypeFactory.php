<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AnalyticType;
use Faker\Generator as Faker;

$factory->define(AnalyticType::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'units' => $faker->name(),
        'is_numeric' => $faker->boolean(),
        'num_decimal_places' => $faker->randomNumber(1),
    ];
});
