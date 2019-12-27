<?php

use Illuminate\Database\Seeder;
use App\Models\Correo;

class CorreoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('correos')->delete();

        Correo::create([
            'correo' => 'genencia.sistemas@hospitalleonbecerra.org',
            'contrasena' => bcrypt('@dmin'),
            'estado' => 'En uso',
            'cedula' => '0584721925'
        ]);
        Correo::create([
            'correo' => 'dptosistemas@hospitalleonbecerra.org',
            'contrasena' => bcrypt('ralvarezc'),
            'estado' => 'Inactivo',
            'cedula' => '0584721925'
        ]);
        Correo::create([
            'correo' => 'soporte.sistemas@hospitalleonbecerra.org',
            'contrasena' => bcrypt('Prueb@'),
            'estado' => 'En uso',
            'cedula' => '1358978564'
        ]);
        Correo::create([
            'correo' => 'analista.sistemas@hospitalleonbecerra.org',
            'contrasena' => bcrypt('mmend21'),
            'estado' => 'En uso',
            'cedula' => '1328169874'
        ]);

        Correo::create([
            'correo' => 'jefe.finanzas@hospitalleonbecerra.org',
            'contrasena' => bcrypt('financiero98'),
            'estado' => 'En uso',
            'cedula' => '1205478963'
        ]);
        Correo::create([
            'correo' => 'secretaria.finanzas@hospitalleonbecerra.org',
            'contrasena' => bcrypt('asistfin'),
            'estado' => 'En uso',
            'cedula' => '0258463258'
        ]);
        Correo::create([
            'correo' => 'gerente.general@hospitalleonbecerra.org',
            'contrasena' => bcrypt('supadmin'),
            'estado' => 'En uso',
            'cedula' => '0784361981'
        ]);
        Correo::create([
            'correo' => 'administracion@hospitalleonbecerra.org',
            'contrasena' => bcrypt('adming'),
            'estado' => 'En uso',
            'cedula' => '1478523458'
        ]);
        Correo::create([
            'correo' => 'laboratorio@hospitalleonbecerra.org',
            'contrasena' => bcrypt('labPrueb@'),
            'estado' => 'En uso',
            'cedula' => '0325896347'
        ]);
        Correo::create([
            'correo' => 'auditoria@hospitalleonbecerra.org',
            'contrasena' => bcrypt('auditint'),
            'estado' => 'En uso',
            'cedula' => '1630258746'
        ]);
        Correo::create([
            'correo' => 'dietetica@hospitalleonbecerra.org',
            'contrasena' => bcrypt('Dprueba'),
            'estado' => 'En uso',
            'cedula' => '1589635784'
        ]);
    }
}
