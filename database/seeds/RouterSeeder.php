<?php

use Illuminate\Database\Seeder;
use App\Models\Router;

class RouterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('routers')->delete();

        Router::create([
            'nombre' => 'ADMIN',
            'pass' => 'mipc2',
            'puerta_enlace' => '192.168.0.0',
            'usuario' => 'admin',
            'clave' => 'admin',
            'id_equipo' => 13
        ]);
        Router::create([
            'nombre' => 'HOSPITAL',
            'pass' => 'prueb@',
            'puerta_enlace' => '192.168.0.0',
            'usuario' => 'admin',
            'clave' => '1234',
            'id_equipo' => 14
        ]);
        Router::create([
            'nombre' => 'HOSPITALBECERRA',
            'pass' => 'prueb@',
            'puerta_enlace' => '192.168.3.0',
            'usuario' => 'admin',
            'clave' => 'admin',
            'id_equipo' => 15
        ]);
        Router::create([
            'nombre' => 'PUNTO1',
            'pass' => 'mipc',
            'puerta_enlace' => '192.168.1.0',
            'usuario' => 'admin',
            'clave' => 'admin',
            'id_equipo' => 16
        ]);
        Router::create([
            'nombre' => 'SISTEMAS',
            'pass' => 'dptosist',
            'puerta_enlace' => '10.0.0.0',
            'usuario' => 'sistemas',
            'clave' => 'sist',
            'id_equipo' => 17
        ]);
        Router::create([
            'nombre' => 'MANTENIMIENTO',
            'pass' => 'dpto1',
            'puerta_enlace' => '192.168.3.0',
            'usuario' => 'admin',
            'clave' => '1234',
            'id_equipo' => 18
        ]);
        Router::create([
            'nombre' => 'LABORATORIO',
            'pass' => 'lab50',
            'puerta_enlace' => '192.168.5.0',
            'usuario' => 'admin',
            'clave' => 'lablb',
            'id_equipo' => 19
        ]);
    }
}
