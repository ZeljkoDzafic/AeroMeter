<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'admin' => false
    ];
});

$factory->define(App\Station::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'unique_id' => $faker->uuid
    ];
});

$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(App\Aerometric::class, function (Faker\Generator $faker) {
    return [
        'temperature' => $faker->randomFloat(1, 1, 99),
        'pressure' => $faker->randomFloat(1, 1, 99),
        'altitude' => $faker->randomFloat(1, 1, 99),
        'insolation' => $faker->randomFloat(1, 1, 99),
        'humidity' => $faker->randomFloat(1, 1, 99),
        'co' => $faker->randomFloat(1, 1, 99),
        'co2' => $faker->randomFloat(1, 1, 99),
        'methane' => $faker->randomFloat(1, 1, 99),
        'butane' => $faker->randomFloat(1, 1, 99),
        'propane' => $faker->randomFloat(1, 1, 99),
        'benzene' => $faker->randomFloat(1, 1, 99),
        'ethanol' => $faker->randomFloat(1, 1, 99),
        'alcohol' => $faker->randomFloat(1, 1, 99),
        'hydrogen' => $faker->randomFloat(1, 1, 99),
        'ozone' => $faker->randomFloat(1, 1, 99),
        'cng' => $faker->randomFloat(1, 1, 99),
        'lpg' => $faker->randomFloat(1, 1, 99),
        'coal_gas' => $faker->randomFloat(1, 1, 99),
        'smoke' => $faker->randomFloat(1, 1, 99),
        'created_at' => $faker->dateTimeBetween('-1 month', 'now')
    ];
});