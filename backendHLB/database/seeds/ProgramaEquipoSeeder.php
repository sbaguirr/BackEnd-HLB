<?php

use Illuminate\Database\Seeder;
use App\Models\ProgramaEquipo;

class ProgramaEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('programa_equipos')->delete();
        
        ProgramaEquipo::create([
            'fecha_instalacion' => '2018-11-25',
            'id_programa' => 3,
            'id_equipo' => 43
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2019-05-23',
            'id_programa' => 4,
            'id_equipo' => 44
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 1,
            'id_equipo' => 45
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 2,
            'id_equipo' => 20
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2018-11-25',
            'id_programa' => 1,
            'id_equipo' => 43
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2019-05-23',
            'id_programa' => 2,
            'id_equipo' => 21
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 7,
            'id_equipo' => 23
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 12,
            'id_equipo' => 22
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2018-11-25',
            'id_programa' => 10,
            'id_equipo' => 43
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2019-05-23',
            'id_programa' => 4,
            'id_equipo' => 20
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 1,
            'id_equipo' => 23
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 2,
            'id_equipo' => 24
        ]);
    }
}
