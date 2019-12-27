<?php

use Illuminate\Database\Seeder;
use App\Models\DetalleEquipo;

class DetalleEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('detalle_equipos')->delete();

        DetalleEquipo::create([
            'services_pack' => 'si',
            'so' => 'Windows 7 Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Admin-PC',
            'id_equipo' => 1
        ]);
        DetalleEquipo::create([
            'services_pack' => 'si',
            'so' => 'Windows 10 Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Laboratorio 1-PC',
            'id_equipo' => 5
        ]);
        DetalleEquipo::create([
            'services_pack' => 'si',
            'so' => 'Windows 7 Professional',
            'tipo_so' => '32 Bits',
            'nombre_pc' => 'Soporte-PC',
            'id_equipo' => 7
        ]);
    }
}
