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
            'id_equipo' => 1
        ]);
        Router::create([
            'nombre' => 'HOSPITAL',
            'pass' => 'prueb@',
            'puerta_enlace' => '192.168.0.0',
            'id_equipo' => 5
        ]);
    }
}
