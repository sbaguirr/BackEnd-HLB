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
            'services_pack' => '1',
            'licencia' => '0', 
            'so' => 'Windows 7 Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Admin-PC',
            'usuario_pc' => 'ADMINHLB',
            'id_equipo' => 20
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',            
            'licencia' => '0', 
            'so' => 'Windows 10 Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Laboratorio 1-PC',            
            'usuario_pc' => 'LAB-HLB',
            'id_equipo' => 21
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',           
            'licencia' => '1', 
            'so' => 'Windows 7 Professional',
            'tipo_so' => '32 Bits',
            'nombre_pc' => 'Soporte-PC',           
            'usuario_pc' => 'SOPORTE-HLB',
            'id_equipo' => 22
        ]);
        DetalleEquipo::create([
            'services_pack' => '1',           
            'licencia' => '0', 
            'so' => 'Windows 7 Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'UCI-PC',           
            'usuario_pc' => 'UCI-HLB',
            'id_equipo' => 23
        ]);
    }
}
