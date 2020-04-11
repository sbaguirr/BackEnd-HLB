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
            'estado_operativo' => 'O',
            'codigo' => 'HLB_PRC_008',
            'tipo_equipo' => 'CPU',
            'id_marca' => 1,
            'modelo' => 'FOXCONN G41MXE-V',
            'descripcion' => 'Asignado',
            'numero_serie' => '330984',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'ip' =>null,
            'asignado' => null,
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_MON_002',
            'tipo_equipo' => 'Monitor',
            'id_marca' => 2,
            'modelo' => 'W1742ST',
            'descripcion' => 'Asignado',
            'numero_serie' => 'OO6TPTM2B393',
            'encargado_registro' => 'admin',
            'componente_principal' => 1,
            'ip' => null,
            'asignado' => null,
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_TEC_001',
            'tipo_equipo' => 'Teclado',
            'id_marca' => 2,
            'modelo' => 'AA-001',
            'descripcion' => 'Letras borrosas',
            'numero_serie' => '1561941011009',
            'encargado_registro' => 'soporte',
            'componente_principal' => 1,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_MOU_001',
            'tipo_equipo' => 'Mouse',
            'id_marca' => 3,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 1,
            'ip' => null,
            'asignado' => null,
        ]);
        Equipo::create([
            'fecha_registro' => '2018-11-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_PRC_001',
            'tipo_equipo' => 'CPU',
            'id_marca' => 4,
            'modelo' => '',
            'descripcion' => 'Asignado',
            'numero_serie' => '',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1589635784',
            'ip' => 1
        ]);
        Equipo::create([
            'fecha_registro' => '2018-02-21',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_MON_001',
            'tipo_equipo' => 'Monitor',
            'id_marca' => 5,
            'modelo' => '1702',
            'descripcion' => 'Asignado',
            'numero_serie' => '11111',
            'encargado_registro' => 'admin',
            'componente_principal' => 5,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-08-27',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_PRC_002',
            'tipo_equipo' => 'CPU',
            'id_marca' => 1,
            'modelo' => '331984',
            'descripcion' => 'Asignado',
            'numero_serie' => 'LISNIRI',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '0784361981',
            'ip' => 2
        ]);


        Equipo::create([
            'fecha_registro' => '2018-12-07',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_IMP_001',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 6,
            'modelo' => 'L210',
            'descripcion' => 'Equipo asignado',
            'numero_serie' => 'S25K558074',
            'encargado_registro' => 'admin',
            'componente_principal' => 1,
            'ip' => null,
            'asignado' => null
        ]);Equipo::create([
            'fecha_registro' => '2019-09-21',
            'estado_operativo' => 'R',
            'codigo' => 'HLB_IMP_002',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 7,
            'modelo' => 'Aficio MP 301 spf',
            'descripcion' => 'Asignado',
            'numero_serie' => 'C87005280',
            'encargado_registro' => 'admin',
            'componente_principal' => 5,
            'ip' => null,
            'asignado' => null
        ]);Equipo::create([
            'fecha_registro' => '2016-03-15',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_IMP_003',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 8,
            'modelo' => 'MP280',
            'descripcion' => '',
            'numero_serie' => 'ACBT16169',
            'encargado_registro' => 'admin',
            'componente_principal' => 7,
            'ip' => null,
            'asignado' => null
        ]);Equipo::create([
            'fecha_registro' => '2017-10-08',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_IMP_005',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 6,
            'modelo' => 'L210',
            'descripcion' => '',
            'numero_serie' => 'S25K692178',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '0325896347',
            'ip' => null
        ]);Equipo::create([
            'fecha_registro' => '2018-02-04',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_IMP_004',
            'tipo_equipo' => 'Impresora',
            'id_marca' => 5,
            'modelo' => 'LaserJet Pro 400 Color Mfp M475dw',
            'descripcion' => 'Color blanca',
            'numero_serie' => 'CND8FC904W',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1478523458',
            'ip' => null
        ]);

        Equipo::create([
            'fecha_registro' => '2019-12-14',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_ROU_001',
            'tipo_equipo' => 'Router',
            'id_marca' => 14,
            'modelo' => 'DREHRTJSD-S',
            'descripcion' => 'Wifi',
            'numero_serie' => 'CND8FC904W',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '0584721925',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2020-01-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_ROU_002',
            'tipo_equipo' => 'Router',
            'id_marca' => 9,
            'modelo' => 'e900',
            'descripcion' => 'Tipo ASDL',
            'numero_serie' => 'WTY8',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1358978564',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_ROU_003',
            'tipo_equipo' => 'Router',
            'id_marca' => 13,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1328169874',
            'ip' => 3
        ]);
        Equipo::create([
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_ROU_004',
            'tipo_equipo' => 'Router',
            'id_marca' => 10,
            'modelo' => 'DSL-AC68U',
            'descripcion' => 'Router DSL',
            'numero_serie' => 'AC68U48',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_ROU_005',
            'tipo_equipo' => 'Router',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-28',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_ROU_006',
            'tipo_equipo' => 'Router',
            'id_marca' => 9,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_ROU_007',
            'tipo_equipo' => 'Router',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1630258746',
            'ip' => null
        ]);
/*laptop*/
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_LAP_001',
            'tipo_equipo' => 'laptop',
            'id_marca' => 13,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1328169874',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_LAP_002',
            'tipo_equipo' => 'laptop',
            'id_marca' => 10,
            'modelo' => 'DSL-AC68U',
            'descripcion' => 'Router DSL',
            'numero_serie' => 'AC68U48',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1205478963',
            'ip' => 4
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_LAP_003',
            'tipo_equipo' => 'laptop',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-28',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_LAP_004',
            'tipo_equipo' => 'laptop',
            'id_marca' => 9,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'ip' => 2,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_LAP_005',
            'tipo_equipo' => 'laptop',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1630258746',
            'ip' => null
        ]);
        /*ram laptop */
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_RAM_001',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 13,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 20,
            'asignado' => '1328169874',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_RAM_002',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 10,
            'modelo' => 'DSL-AC68U',
            'descripcion' => 'Router DSL',
            'numero_serie' => 'AC68U48',
            'encargado_registro' => 'admin',
            'componente_principal' => 21,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_RAM_003',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'encargado_registro' => 'admin',
            'componente_principal' => 22,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_RAM_004',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'encargado_registro' => 'admin',
            'componente_principal' => 22,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-28',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_RAM_005',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 9,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 23,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_RAM_006',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'encargado_registro' => 'admin',
            'componente_principal' => 24,
            'asignado' => '1630258746',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_RAM_007',
            'tipo_equipo' => 'memoria_ram',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'encargado_registro' => 'admin',
            'componente_principal' => 24,
            'asignado' => '1630258746',
            'ip' => null
        ]);
        /*Procesador laptop */
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_PRC_003',
            'tipo_equipo' => 'procesador',
            'id_marca' => 13,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 20,
            'asignado' => '1328169874',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_PRC_004',
            'tipo_equipo' => 'procesador',
            'id_marca' => 10,
            'modelo' => 'DSL-AC68U',
            'descripcion' => 'Router DSL',
            'numero_serie' => 'AC68U48',
            'encargado_registro' => 'admin',
            'componente_principal' => 21,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_PRC_005',
            'tipo_equipo' => 'procesador',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'encargado_registro' => 'admin',
            'componente_principal' => 22,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-28',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_PRC_006',
            'tipo_equipo' => 'procesador',
            'id_marca' => 9,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 23,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_PRC_007',
            'tipo_equipo' => 'procesador',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'encargado_registro' => 'admin',
            'componente_principal' => 24,
            'asignado' => '1630258746',
            'ip' => null
        ]);
/*dd*/
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DD_001',
            'tipo_equipo' => 'disco_duro',
            'id_marca' => 13,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 20,
            'asignado' => '1328169874',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DD_002',
            'tipo_equipo' => 'disco_duro',
            'id_marca' => 13,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 20,
            'asignado' => '1328169874',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DD_003',
            'tipo_equipo' => 'disco_duro',
            'id_marca' => 10,
            'modelo' => 'DSL-AC68U',
            'descripcion' => 'Router DSL',
            'numero_serie' => 'AC68U48',
            'encargado_registro' => 'admin',
            'componente_principal' => 21,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DD_004',
            'tipo_equipo' => 'disco_duro',
            'id_marca' => 11, 
            'modelo' => 'MU-MIMO',
            'descripcion' => 'Ultra Wifi',
            'numero_serie' => 'AC3150',
            'encargado_registro' => 'admin',
            'componente_principal' => 22,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-28',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_DD_005',
            'tipo_equipo' => 'disco_duro',
            'id_marca' => 9,
            'modelo' => '802.11ac',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 23,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-12-05',
            'estado_operativo' => 'ER',
            'codigo' => 'HLB_DD_006',
            'tipo_equipo' => 'disco_duro',
            'id_marca' => 12,
            'modelo' => 'Nighthawk Pro',
            'descripcion' => 'Wifi, Tipo VSDL',
            'numero_serie' => 'XR500',
            'encargado_registro' => 'admin',
            'componente_principal' => 24,
            'asignado' => '1630258746',
            'ip' => null
        ]);

        
        Equipo::create([//43
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_DSK_001',
            'tipo_equipo' => 'desktop',
            'id_marca' => 7,
            'modelo' => 'XSAFEG',
            'descripcion' => 'Primera computadora de sistemas',
            'numero_serie' => '4534592050346',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([//44
            'fecha_registro' => '2017-06-13',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DSK_002',
            'tipo_equipo' => 'desktop',
            'id_marca' => 5,
            'modelo' => 'DSL-AC3432468U',
            'descripcion' => 'Desktop con IP asignada',
            'numero_serie' => '5675757543',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '1205478963',
            'ip' => 4
        ]);
        Equipo::create([//45
            'fecha_registro' => '2018-09-25',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_DSK_003',
            'tipo_equipo' => 'desktop',
            'id_marca' => 1, 
            'modelo' => 'RTHGFD.2',
            'descripcion' => 'Con UPS',
            'numero_serie' => 'T45645DG867FH',
            'encargado_registro' => 'admin',
            'componente_principal' => null,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_MON_010',
            'tipo_equipo' => 'monitor',//46
            'id_marca' => 2,
            'modelo' => 'W1742ST',
            'descripcion' => 'Asignado',
            'numero_serie' => 'OO6TPTM2B393',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'ip' => null,
            'asignado' => null,
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_TEC_010',
            'tipo_equipo' => 'teclado',//47
            'id_marca' => 12,
            'modelo' => 'AA-001',
            'descripcion' => 'Letras borrosas',
            'numero_serie' => '1561941011009',
            'encargado_registro' => 'soporte',
            'componente_principal' => 43,
            'ip' => null,
            'asignado' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_MOU_010',
            'tipo_equipo' => 'mouse',//48
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'ip' => null,
            'asignado' => null,
        ]); 
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_PAR_010',
            'tipo_equipo' => 'parlantes',//49
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'ip' => null,
            'asignado' => null,
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_TMA_010',
            'tipo_equipo' => 'tarjeta_madre',//50
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'ip' => null,
            'asignado' => null,
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_PRC_010',
            'tipo_equipo' => 'procesador',//51
            'id_marca' => 8,
            'modelo' => 'PREF',
            'descripcion' => 'Revisar',
            'numero_serie' => 'FGvsg.s',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_RAM_010',
            'tipo_equipo' => 'memoria_ram',//52
            'id_marca' => 10,
            'modelo' => 'KDAME',
            'descripcion' => 'Revisar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_RAM_011',
            'tipo_equipo' => 'memoria_ram',//53
            'id_marca' => 1,
            'modelo' => 'KDAME',
            'descripcion' => 'Revisar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_RAM_012',
            'tipo_equipo' => 'memoria_ram',//54
            'id_marca' => 4,
            'modelo' => 'RYKERWFS',
            'descripcion' => 'Revisar',
            'numero_serie' => '234536756-D',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_DD_010',
            'tipo_equipo' => 'disco_duro',//55
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => 'MEKTDS',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_CAS_010',
            'tipo_equipo' => 'case',//56
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'etiquetar',
            'numero_serie' => '246-FDX',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_FPO_010',
            'tipo_equipo' => 'fuente_poder',//57
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'limpiar',
            'numero_serie' => 'FFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_TRE_010',
            'tipo_equipo' => 'tarjeta_red',//58
            'id_marca' => 10,
            'modelo' => '7654GE',
            'descripcion' => 'cambiar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'O',
            'codigo' => 'HLB_UPS_010',
            'tipo_equipo' => 'ups',//59
            'id_marca' => 5,
            'modelo' => '11ac',
            'descripcion' => 'UPS-reg',
            'numero_serie' => '24SED',
            'encargado_registro' => 'admin',
            'componente_principal' => 43,
            'asignado' => null,
            'ip' => null
        ]);

        /*dektop 2*/
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_MON_011',
            'tipo_equipo' => 'monitor',//60
            'id_marca' => 2,
            'modelo' => 'W1742ST',
            'descripcion' => 'Asignado',
            'numero_serie' => 'OO6TPTM2B393',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'ip' => null,
            'asignado' => '1205478963',
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_TEC_011',
            'tipo_equipo' => 'teclado',//61
            'id_marca' => 2,
            'modelo' => 'AA-001',
            'descripcion' => 'Letras borrosas',
            'numero_serie' => '1561941011009',
            'encargado_registro' => 'soporte',
            'componente_principal' => 44,
            'ip' => null,
            'asignado' => '1205478963'
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_MOU_011',
            'tipo_equipo' => 'mouse',//62
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'ip' => null,
            'asignado' => '1205478963',
        ]); 
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_PAR_011',
            'tipo_equipo' => 'parlantes',//63
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'ip' => null,
            'asignado' => '1205478963',
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_TMA_011',
            'tipo_equipo' => 'tarjeta_madre',//64
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'ip' => null,
            'asignado' => '1205478963',
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_PRC_011',
            'tipo_equipo' => 'procesador',//65
            'id_marca' => 8,
            'modelo' => 'PREF',
            'descripcion' => 'Revisar',
            'numero_serie' => 'FGvsg.s',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_RAM_013',
            'tipo_equipo' => 'memoria_ram',//66
            'id_marca' => 10,
            'modelo' => 'KDAME',
            'descripcion' => 'Revisar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_RAM_014',
            'tipo_equipo' => 'memoria_ram',//67
            'id_marca' => 1,
            'modelo' => 'KDAME',
            'descripcion' => 'Revisar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DD_011',
            'tipo_equipo' => 'disco_duro',//68
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => 'MEKTDS',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_DD_012',
            'tipo_equipo' => 'disco_duro',//69
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => 'MEKTDS',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_CAS_011',
            'tipo_equipo' => 'case',//70
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'etiquetar',
            'numero_serie' => '246-FDX',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_FPO_011',
            'tipo_equipo' => 'fuente_poder',//71
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'limpiar',
            'numero_serie' => 'FFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_TRE_011',
            'tipo_equipo' => 'tarjeta_red',//72
            'id_marca' => 10,
            'modelo' => '7654GE',
            'descripcion' => 'cambiar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'D',
            'codigo' => 'HLB_REG_011',
            'tipo_equipo' => 'regulador',//73
            'id_marca' => 5,
            'modelo' => '11ac',
            'descripcion' => 'REVISAR',
            'numero_serie' => 'SD-FR-TR-ED',
            'encargado_registro' => 'admin',
            'componente_principal' => 44,
            'asignado' => '1205478963',
            'ip' => null
        ]);


        /*dektop 3*/
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_MON_012',
            'tipo_equipo' => 'monitor',//74
            'id_marca' => 2,
            'modelo' => 'W1742ST',
            'descripcion' => 'Asignado',
            'numero_serie' => 'OO6TPTM2B393',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'ip' => null,
            'asignado' => '0258463258',
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_TEC_012',
            'tipo_equipo' => 'teclado',//75
            'id_marca' => 2,
            'modelo' => 'AA-001',
            'descripcion' => 'Letras borrosas',
            'numero_serie' => '1561941011009',
            'encargado_registro' => 'soporte',
            'componente_principal' => 45,
            'ip' => null,
            'asignado' => '0258463258'
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_MOU_012',
            'tipo_equipo' => 'mouse',//76
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'ip' => null,
            'asignado' => '0258463258',
        ]); 
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_PAR_012',
            'tipo_equipo' => 'parlantes',//77
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'ip' => null,
            'asignado' => '0258463258',
        ]);
        Equipo::create([
            'fecha_registro' => '2019-04-15',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_TMA_012',
            'tipo_equipo' => 'tarjeta_madre',//78
            'id_marca' => 7,
            'modelo' => 'BB-001',
            'descripcion' => 'Asignado',
            'numero_serie' => 'X75784406542',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'ip' => null,
            'asignado' => '0258463258',
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_PRC_012',
            'tipo_equipo' => 'procesador',//79
            'id_marca' => 8,
            'modelo' => 'PREF',
            'descripcion' => 'Revisar',
            'numero_serie' => 'FGvsg.s',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_RAM_023',
            'tipo_equipo' => 'memoria_ram',//80
            'id_marca' => 10,
            'modelo' => 'KDAME',
            'descripcion' => 'Revisar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_DD_020',
            'tipo_equipo' => 'disco_duro',//81
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'NN',
            'numero_serie' => 'MEKTDS',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_CAS_012',
            'tipo_equipo' => 'case',//82
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'etiquetar',
            'numero_serie' => '246-FDX',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_FPO_012',
            'tipo_equipo' => 'fuente_poder',//83
            'id_marca' => 10,
            'modelo' => '802ew.11ac',
            'descripcion' => 'limpiar',
            'numero_serie' => 'FFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'asignado' => '0258463258',
            'ip' => null
        ]);
        Equipo::create([
            'fecha_registro' => '2019-05-07',
            'estado_operativo' => 'B',
            'codigo' => 'HLB_TRE_012',
            'tipo_equipo' => 'tarjeta_red',//84
            'id_marca' => 10,
            'modelo' => '7654GE',
            'descripcion' => 'cambiar',
            'numero_serie' => '4668EGFFX',
            'encargado_registro' => 'admin',
            'componente_principal' => 45,
            'asignado' => '0258463258',
            'ip' => null
        ]);
    }
}
