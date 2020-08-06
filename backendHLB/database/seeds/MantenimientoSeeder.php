<?php

use Illuminate\Database\Seeder;
use App\Models\Mantenimiento;

class MantenimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mantenimientos')->delete();
        Mantenimiento::create([
            'titulo' => 'Dispositivo no prende',
            'tipo' => 'C', //correctivo
            'fecha_inicio' => '2020-04-15',
            'fecha_fin' => '2020-04-15',
            'observacion_falla' => 'Falla desconocida',
            'estado_fisico' => 'El equipo luce bien en general',
            'actividad_realizada' => 'Se examinó el equipo',
            'observacion' => 'Cambiar de ubicación al equipo',
            'id_equipo' => 59,
            'realizado_por' => 'soporte',
        ]);
    }
}
