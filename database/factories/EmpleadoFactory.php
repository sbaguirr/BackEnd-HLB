<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Empleado;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Empleado::class, function (Faker $faker) {
    return [
        'cedula' => Str::random(10),
        'nombre' => $faker->name,
        'apellido' => $faker->lastName,
        'id_departamento' => 0
    ];
});
