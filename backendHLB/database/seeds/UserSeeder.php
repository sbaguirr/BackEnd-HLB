<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'id_rol' => 1,
            "estado" => 'A',
            'cedula' => '0584721925'
        ]);
        User::create([
            'username' => 'soporte',
            'password' => bcrypt('sistemas2'),
            'id_rol' => 2,
            "estado" => 'A',
            'cedula' => '1358978564'
        ]);
        User::create([
            'username' => 'analista',
            'password' => bcrypt('sistemas1'),
            'id_rol' => 2,
            "estado" => 'A',
            'cedula' => '1328169874'
        ]);
        User::create([
            'username' => 'userfinanzas',
            'password' => bcrypt('financ3'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '1205478963'
        ]);
        user::create([
            'username' => 'asistente',
            'password' => bcrypt('finanzas1'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '0258463258'
        ]);
        User::create([
            'username' => 'superadmin',
            'password' => bcrypt('admin'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '0784361981'
        ]);
        User::create([
            'username' => 'administracion',
            'password' => bcrypt('admin'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '1478523458'
        ]);
        User::create([
            'username' => 'laboratorio1',
            'password' => bcrypt('lab1234'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '0325896347'
        ]);
        User::create([
            'username' => 'auditoria',
            'password' => bcrypt('auint'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '1630258746'
        ]);
        User::create([
            'username' => 'dietetica',
            'password' => bcrypt('dptodiet1'),
            'id_rol' => 3,
            "estado" => 'A',
            'cedula' => '1589635784'
        ]);
        User::create([
            'username' => 'finanzas',
            'password' => bcrypt('finanzas'),
            'id_rol' => 5,
            "estado" => 'A',
            'cedula' => '1589635781'
        ]);
    }
}
