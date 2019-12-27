<?php

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->delete();

        Usuario::create([
            'usuario' => 'admin',
            'contrasena' => bcrypt('admin'),
            'id_rol' => 1,
            'cedula' => '0584721925'
        ]);
        Usuario::create([
            'usuario' => 'soporte',
            'contrasena' => bcrypt('sistemas2'),
            'id_rol' => 2,
            'cedula' => '1358978564'
        ]);
        Usuario::create([
            'usuario' => 'analista',
            'contrasena' => bcrypt('sistemas1'),
            'id_rol' => 2,
            'cedula' => '1328169874'
        ]);
        Usuario::create([
            'usuario' => 'userfinanzas',
            'contrasena' => bcrypt('financ3'),
            'id_rol' => 3,
            'cedula' => '1205478963'
        ]);
        Usuario::create([
            'usuario' => 'asistente',
            'contrasena' => bcrypt('finanzas1'),
            'id_rol' => 3,
            'cedula' => '0258463258'
        ]);
        Usuario::create([
            'usuario' => 'superadmin',
            'contrasena' => bcrypt('admin'),
            'id_rol' => 3,
            'cedula' => '0784361981'
        ]);
        Usuario::create([
            'usuario' => 'administracion',
            'contrasena' => bcrypt('admin'),
            'id_rol' => 3,
            'cedula' => '1478523458'
        ]);
        Usuario::create([
            'usuario' => 'laboratorio1',
            'contrasena' => bcrypt('lab1234'),
            'id_rol' => 3,
            'cedula' => '0325896347'
        ]);
        Usuario::create([
            'usuario' => 'auditoria',
            'contrasena' => bcrypt('auint'),
            'id_rol' => 3,
            'cedula' => '1630258746'
        ]);
        Usuario::create([
            'usuario' => 'dietetica',
            'contrasena' => bcrypt('dptodiet1'),
            'id_rol' => 3,
            'cedula' => '1589635784'
        ]);
    }
}
