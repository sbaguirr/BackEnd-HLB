<?php

use Illuminate\Database\Seeder;
use App\Models\Ip;

class IpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ips')->delete();

        Ip::create([
            'estado' => 'En uso',
            'fecha_asignacion' => '2019-12-12 10:10:00',
            'direccion_ip' => '192.168.1.1',
            'hostname' => '',
            'subred' => '',
            'fortigate' => '',
            'observacion' => '',
            'maquinas_adicionales' => 0,
            'nombre_usuario' => '',
            'encargado_registro' => 'admin'
        ]);
        Ip::create([
            'estado' => 'Libre',
            'fecha_asignacion' => '2017-03-29 12:10:00',
            'direccion_ip' => '192.168.1.2',
            'hostname' => 'Asistente_finan',
            'subred' => '',
            'fortigate' => 'ADMINISTRACION_KATHIUSKA_QUINDE',
            'observacion' => '',
            'maquinas_adicionales' => 0,
            'nombre_usuario' => 'Ing. Kathiuska Quinde',
            'encargado_registro' => 'admin'
        ]);
        Ip::create([
            'estado' => 'En uso',
            'fecha_asignacion' => '2017-08-17 15:10:00',
            'direccion_ip' => '192.168.1.3',
            'hostname' => 'GrupoPrivado',
            'subred' => '192.168.0.0',
            'fortigate' => 'ADMINISTRACION_ROUTER_GRUPO_PRIVADO',
            'observacion' => 'Aplica solo para los router',
            'maquinas_adicionales' => 1,
            'nombre_usuario' => 'Administracion',
            'encargado_registro' => 'soporte'
        ]);
        Ip::create([
            'estado' => 'En uso',
            'fecha_asignacion' => '2018-10-22 11:15:00',
            'direccion_ip' => '192.168.1.4',
            'hostname' => 'UCI',
            'subred' => '192.168.0.0',
            'fortigate' => 'UCI_ROUTER_UCI',
            'observacion' => '',
            'maquinas_adicionales' => 1,
            'nombre_usuario' => 'UCI',
            'encargado_registro' => 'admin'
        ]);
        Ip::create([
            'estado' => 'En uso',
            'fecha_asignacion' => '2014-10-27 18:00:00',
            'direccion_ip' => '192.168.1.5',
            'hostname' => 'BSPI_1-PC',
            'subred' => '',
            'fortigate' => '',
            'observacion' => 'Pasante: María',
            'maquinas_adicionales' => 0,
            'nombre_usuario' => 'Pasante',
            'encargado_registro' => 'soporte'
        ]);
    }
}
