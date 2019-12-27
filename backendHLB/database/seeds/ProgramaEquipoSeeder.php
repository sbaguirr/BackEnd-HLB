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
            'id_equipo' => 1
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2019-05-23',
            'id_programa' => 4,
            'id_equipo' => 1
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 1,
            'id_equipo' => 1
        ]);
        ProgramaEquipo::create([
            'fecha_instalacion' => '2017-04-04',
            'id_programa' => 2,
            'id_equipo' => 5
        ]);
    }
}
