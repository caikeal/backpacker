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
        'password' => bcrypt("123456"),
        'remember_token' => str_random(10),
        'phone' => "139".rand(0,9).rand(0,9)."21".rand(0,9)."53".rand(0,9),
        'poster' => $faker->imageUrl(),
    ];
});
