<?php

use Illuminate\Database\Seeder;
use App\Models\EstadoEquipo;

class EstadoEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estado_equipo')->delete();

        /**
         * Estados de Ips
         */
        EstadoEquipo::create([
            'nombre' => 'En Uso',
            'abreviatura' => 'EU',
        ]);

        EstadoEquipo::create([
            'nombre' => 'Libre',
            'abreviatura' => 'L',
        ]);
        /**
         * Estado de Equipos
         */
        EstadoEquipo::create([
            'nombre' => 'Operativo',
            'abreviatura' => 'O',
        ]);
        EstadoEquipo::create([
            'nombre' => 'En Revision',
            'abreviatura' => 'ER',
        ]);
        EstadoEquipo::create([
            'nombre' => 'Reparado',
            'abreviatura' => 'R',
        ]);
        EstadoEquipo::create([
            'nombre' => 'de Baja',
            'abreviatura' => 'B',
        ]);
        EstadoEquipo::create([
            'nombre' => 'Disponible',
            'abreviatura' => 'D',
        ]);
    }
}
