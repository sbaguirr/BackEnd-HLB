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
            'contrasena' => '@dmin',
            'estado' => 'EU',
            'cedula' => '0584721925'
        ]);
        Correo::create([
            'correo' => 'dptosistemas@hospitalleonbecerra.org',
            'contrasena' => 'ralvarezc',
            'estado' => 'I',
            'cedula' => '0584721925'
        ]);
        Correo::create([
            'correo' => 'soporte.sistemas@hospitalleonbecerra.org',
            'contrasena' => 'Prueb@',
            'estado' => 'EU',
            'cedula' => '1358978564'
        ]);
        Correo::create([
            'correo' => 'analista.sistemas@hospitalleonbecerra.org',
            'contrasena' => 'mmend21',
            'estado' => 'EU',
            'cedula' => '1328169874'
        ]);

        Correo::create([
            'correo' => 'jefe.finanzas@hospitalleonbecerra.org',
            'contrasena' => 'financiero98',
            'estado' => 'EU',
            'cedula' => '1205478963'
        ]);
        Correo::create([
            'correo' => 'secretaria.finanzas@hospitalleonbecerra.org',
            'contrasena' => 'asistfin',
            'estado' => 'EU',
            'cedula' => '0258463258'
        ]);
        Correo::create([
            'correo' => 'gerente.general@hospitalleonbecerra.org',
            'contrasena' => 'supadmin',
            'estado' => 'EU',
            'cedula' => '0784361981'
        ]);
        Correo::create([
            'correo' => 'administracion@hospitalleonbecerra.org',
            'contrasena' =>'adming',
            'estado' => 'EU',
            'cedula' => '1478523458'
        ]);
        Correo::create([
            'correo' => 'laboratorio@hospitalleonbecerra.org',
            'contrasena' => 'labPrueb@',
            'estado' => 'EU',
            'cedula' => '0325896347'
        ]);
        Correo::create([
            'correo' => 'auditoria@hospitalleonbecerra.org',
            'contrasena' => 'auditint',
            'estado' => 'EU',
            'cedula' => '1630258746'
        ]);
        Correo::create([
            'correo' => 'dietetica@hospitalleonbecerra.org',
            'contrasena' => 'Dprueba',
            'estado' => 'EU',
            'cedula' => '1589635784'
        ]);
    }
}
