<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    $capital = $faker->randomFloat(2, 3000, 20000);
    return [
        'name'              => $faker->name,
        'address'           => $faker->address,
        'phone'             => $faker->phoneNumber,
        'capitalization'    => $capital,
        'loan'              => $faker->randomFloat(2, 0, $capital)
    ];
});
