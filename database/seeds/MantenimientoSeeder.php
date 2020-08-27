<?php

use Illuminate\Database\Seeder;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\DB;

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
            'fecha_inicio' => '2020-05-15',
            'fecha_fin' => '2020-05-15',
            'observacion_falla' => 'Falla desconocida',
            'estado_fisico' => 'El equipo luce bien en general',
            'actividad_realizada' => 'Se examinó el equipo',
            'observacion' => 'Cambiar de ubicación al equipo',
            'id_equipo' => 59,
            'realizado_por' => 'soporte',
        ]);

        Mantenimiento::create([
            'titulo' => 'Dispositivo no prende',
            'tipo' => 'C', //correctivo
            'fecha_inicio' => '2020-06-15',
            'fecha_fin' => '2020-06-15',
            'observacion_falla' => 'Falla desconocida',
            'estado_fisico' => 'El equipo luce bien en general',
            'actividad_realizada' => 'Se examinó el equipo',
            'observacion' => 'Cambiar de ubicación al equipo',
            'id_equipo' => 8,
            'realizado_por' => 'soporte',
        ]);

        Mantenimiento::create([
            'titulo' => 'Limpieza del equipo',
            'tipo' => 'P', //correctivo
            'fecha_inicio' => '2020-07-15',
            'fecha_fin' => '2020-07-15',
            'observacion_falla' => 'Falla desconocida',
            'estado_fisico' => 'El equipo luce bien en general',
            'actividad_realizada' => 'Se examinó el equipo',
            'observacion' => 'Cambiar de ubicación al equipo',
            'id_equipo' => 8,
            'realizado_por' => 'soporte',
        ]);

        Mantenimiento::create([
            'titulo' => 'Problemas de impresión',
            'tipo' => 'R', //correctivo
            'fecha_inicio' => '2020-07-10',
            'fecha_fin' => '2020-07-10',
            'observacion_falla' => 'Falla desconocida',
            'estado_fisico' => 'El equipo luce bien en general',
            'actividad_realizada' => 'Se examinó el equipo',
            'observacion' => 'Cambiar de ubicación al equipo',
            'id_equipo' => 8,
            'realizado_por' => 'soporte',
        ]);
    }
}
/*
*/