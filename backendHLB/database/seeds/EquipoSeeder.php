<?php

use Illuminate\Database\Seeder;
use App\Models\Equipo;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('equipos')->delete();

        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'Operativo',
            'codigo' => 'HLB-50',
            'tipo_equipo' => 'CPU',
            'id_marca' => 1,
            'modelo' => 'FOXCONN G41MXE-V',
            'descripcion' => 'Asignado',
            'numero_serie' => '330984',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => 5
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'Operativo',
            'codigo' => 'HLB0009',
            'tipo_equipo' => 'Monitor',
            'id_marca' => 2,
            'modelo' => 'W1742ST',
            'descripcion' => 'Asignado',
            'numero_serie' => 'OO6TPTM2B393',
            'componente_principal' => 1,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'Operativo',
            'codigo' => '',
            'tipo_equipo' => 'Teclado',
            'id_marca' => 2,
            'modelo' => '',
            'descripcion' => 'Letras borrosas',
            'numero_serie' => '1561941011009',
            'componente_principal' => 1,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'Operativo',
            'codigo' => 'leonb5',
            'tipo_equipo' => 'Mouse',
            'id_marca' => 3,
            'modelo' => '',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'componente_principal' => 1,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-11-15',
            'estado_operativo' => 'Operativo',
            'codigo' => '',
            'tipo_equipo' => 'CPU',
            'id_marca' => 4,
            'modelo' => '',
            'descripcion' => 'Asignado',
            'numero_serie' => '',
            'componente_principal' => null,
            'encargado_registro' => 'soporte',
            'ip' => 1
        ]);
        Equipo::create([
            'fecha_registro' => '2018-02-21',
            'estado_operativo' => 'Operativo',
            'codigo' => 'DG41TY',
            'tipo_equipo' => 'Monitor',
            'id_marca' => 5,
            'modelo' => '1702',
            'descripcion' => 'Asignado',
            'numero_serie' => '',
            'componente_principal' => 5,
            'encargado_registro' => 'soporte',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-08-27',
            'estado_operativo' => 'Operativo',
            'codigo' => 'G41MXE-V',
            'tipo_equipo' => 'CPU',
            'id_marca' => 1,
            'modelo' => '331984',
            'descripcion' => 'Asignado',
            'numero_serie' => 'LISNIRI',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => 2
        ]);


        Equipo::create([
            'fecha_registro' => '2018-12-07',
            'estado_operativo' => 'En revisión',
            'codigo' => 'THDTGH',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 6,
            'modelo' => 'L210',
            'descripcion' => 'Equipo asignado',
            'numero_serie' => 'S25K558074',
            'componente_principal' => 1,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);Equipo::create([
            'fecha_registro' => '2019-09-21',
            'estado_operativo' => 'Reparado',
            'codigo' => 'HLB4',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 7,
            'modelo' => 'Aficio MP 301 spf',
            'descripcion' => 'Asignado',
            'numero_serie' => 'C87005280',
            'componente_principal' => 5,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);Equipo::create([
            'fecha_registro' => '2016-03-15',
            'estado_operativo' => 'De baja',
            'codigo' => 'HBL3',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 8,
            'modelo' => 'MP280',
            'descripcion' => '',
            'numero_serie' => 'ACBT16169',
            'componente_principal' => 7,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);Equipo::create([
            'fecha_registro' => '2017-10-08',
            'estado_operativo' => 'Disponible',
            'codigo' => 'HLB1',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 6,
            'modelo' => 'L210',
            'descripcion' => '',
            'numero_serie' => 'S25K692178',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);Equipo::create([
            'fecha_registro' => '2018-02-04',
            'estado_operativo' => 'Operativo',
            'codigo' => 'HLB0',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 5,
            'modelo' => 'LaserJet Pro 400 Color Mfp M475dw',
            'descripcion' => 'Color blanca',
            'numero_serie' => 'CND8FC904W',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);

        Equipo::create([
            'fecha_registro' => '2019-12-14',
            'estado_operativo' => 'Disponible',
            'codigo' => 'HLB-1',
            'tipo_equipo' => 'Router',
            'id_marca' => 14,
            'modelo' => 'DREHRTJSD-S',
            'descripcion' => 'Wifi',
            'numero_serie' => 'CND8FC904W',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2020-01-07',
            'estado_operativo' => 'Disponible',
            'codigo' => 'HLB-2010',
            'tipo_equipo' => 'Router',
            'id_marca' => 9,
            'modelo' => 'e900',
            'descripcion' => 'Tipo ASDL',
            'numero_serie' => 'WTY8',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'Disponible',
            'codigo' => 'HLB-8',
            'tipo_equipo' => 'Router',
            'id_marca' => 13,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'Disponible',
            'codigo' => 'a4510',
            'tipo_equipo' => 'Router',
            'id_marca' => 10,
            'modelo' => 'DSL-AC68U',
            'descripcion' => 'Router DSL',
            'numero_serie' => 'AC68U48',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'Disponible',
            'codigo' => 'HLB-2',
            'tipo_equipo' => 'Router',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-28',
            'estado_operativo' => 'Operativo',
            'codigo' => 'HLB508798',
            'tipo_equipo' => 'Router',
            'id_marca' => 9,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'En revisión',
            'codigo' => 'HLEON2',
            'tipo_equipo' => 'Router',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'componente_principal' => null,
            'encargado_registro' => 'admin',
            'ip' => null
        ]);
    }
}
