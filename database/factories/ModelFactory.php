<?php

use App\Chart;
use App\Commander;
use App\Location;
use App\Planet;
use App\Ship;
use Carbon\Carbon;

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Commander::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'credits' => $faker->numberBetween(500,10000)
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Planet::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->bothify($faker->colorName . '##??'),
        'category' => $faker->randomElement(['Terrestrial', 'Ice Giant', 'Gas Giant'])
    ];
});

$factory->define(Ship::class, function (Faker\Generator $faker) {
    return [
        'commander_id' => factory(Commander::class)->create()->id,
        'name' => $faker->lastName,
        'type' => $faker->bothify($faker->colorName . '-###'),
        'force' => $faker->numberBetween(5000,20000),
        'fuel' => $faker->numberBetween(100,500),
        'mass' => $faker->numberBetween(100,500)
    ];
});

$factory->define(Schedule::class, function (Faker\Generator $faker) {
    return [
        'ship_id' => factory(Ship::class)->create()->id,
        'destination_type' => Planet::class,
        'destination_id' => factory(Planet::class)->create()->id,
        'depart_time' => Carbon::now(),
        'arrival_time' => Carbon::now()->addHours(2)
    ];
});

$factory->define(Location::class, function (Faker\Generator $faker) {
    return [
        'solar_system_id' => 1,
        'object_type' => Planet::class,
        'object_id' => factory(Planet::class)->create()->id,
        'x' => $faker->numberBetween(-100,100),
        'y' => $faker->numberBetween(-100,100)
    ];
});

$factory->define(Chart::class, function (Faker\Generator $faker) {
    return [
        'commander_id' => factory(Commander::class)->create()->id,
        'location_id' => factory(Location::class)->create()->id
    ];
});
