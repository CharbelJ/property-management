<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Property;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'guid' => (string) Str::uuid(),
        'suburb' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
    ];
});
