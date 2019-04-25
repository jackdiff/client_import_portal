<?php

use App\Customer;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'company' => Str::random(50),
        'address' => Str::random(50),
        'no' => Str::random(50),
        'tel' => Str::random(10),
        'mobile_tel' => Str::random(10),
        'position' => Str::random(30),
        'website' => Str::random(100),
        'city' => Str::random(100),
        'sheet_source' => Str::random(30),
        'category_id' => Str::random(10),
    ];
});
