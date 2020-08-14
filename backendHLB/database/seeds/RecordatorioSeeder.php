<?php

use Illuminate\Database\Seeder;
use App\Models\Recordatorio;


class RecordatorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //



        DB::table('recordatorios')->delete();

        Recordatorio::create([
            'hora_recordatorio' => '10:00:00',
            'fecha_recordatorio' => '2020-06-10',
            'estado' => 'A', //Activo
            'id_mantenimiento' => 1
        ]);

        Recordatorio::create([
            'hora_recordatorio' => '10:00:00',
            'fecha_recordatorio' => '2020-07-11',
            'estado' => 'A', //Activo
            'id_mantenimiento' => 1
        ]);

        Recordatorio::create([
            'hora_recordatorio' => '10:00:00',
            'fecha_recordatorio' => '2020-08-08',
            'estado' => 'I', //Inactivo
            'id_mantenimiento' => 1
        ]);

        Recordatorio::create([
            'hora_recordatorio' => '10:00:00',
            'fecha_recordatorio' => '2020-09-10',
            'estado' => 'I', //Inactivo
            'id_mantenimiento' => 1
        ]);


    }
}
