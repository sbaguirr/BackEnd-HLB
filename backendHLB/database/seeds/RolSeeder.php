<?php

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        Rol::create([
            'nombre' => 'Administrador'
        ]);
        Rol::create([
            'nombre' => 'Soporte técnico'
        ]);
        Rol::create([
            'nombre' => 'Empleado institucional'
        ]);
        Rol::create([
            'nombre' => 'Pasante'
        ]);
    }
}
