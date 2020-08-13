<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Organizacion;
use Faker\Generator as Faker;

$factory->define(Organizacion::class, function (Faker $faker) {
    return [
        'bspi_punto' => $faker->name
    ];
});
