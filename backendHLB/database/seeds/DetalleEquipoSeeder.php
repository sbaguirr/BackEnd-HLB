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
            'so' => 'Windows 10 Home Single Language',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Admin-PC',
            'usuario_pc' => 'ADMINHLB',
            'office' => '2007',
            'id_equipo' => 20
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',            
            'licencia' => '0', 
            'so' => 'Windows 7 Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Laboratorio 1-PC',             
            'office' => '2007',           
            'usuario_pc' => 'LAB-HLB',
            'id_equipo' => 21
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',           
            'licencia' => '1', 
            'so' => 'Windows 10 Pro',
            'tipo_so' => '32 Bits',
            'nombre_pc' => 'Soporte-PC',           
            'usuario_pc' => 'SOPORTE-HLB',
            'office' => '2010',
            'id_equipo' => 22
        ]);
        DetalleEquipo::create([
            'services_pack' => '1',           
            'licencia' => '0', 
            'so' => 'Windows 7 Home Basic',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'UCI-PC',           
            'usuario_pc' => 'UCI-HLB',
            'office' => '2013',
            'id_equipo' => 23
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',           
            'licencia' => '0', 
            'so' => 'Windows 10 Pro',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'UCI-PC5',           
            'usuario_pc' => 'UCI-HLB48',
            'office' => '2007',
            'id_equipo' => 24
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',            
            'licencia' => '0', 
            'so' => 'Windows 7 Home Premium',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'Laboratorio 1-PC',            
            'usuario_pc' => 'LAB-HLB',
            'office' => '2016',
            'id_equipo' => 1
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',           
            'licencia' => '1', 
            'so' => 'Windows 7 Ultimate',
            'tipo_so' => '32 Bits',
            'nombre_pc' => 'Soporte-PC',           
            'usuario_pc' => 'SOPORTE-HLB',
            'office' => '2019',
            'id_equipo' => 5
        ]);
        DetalleEquipo::create([
            'services_pack' => '1',           
            'licencia' => '0', 
            'so' => 'Windows 8 Pro with Media Center',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'UCI-PC',           
            'usuario_pc' => 'UCI-HLB',
            'office' => '2010',
            'id_equipo' => 7
        ]);
        DetalleEquipo::create([
            'services_pack' => '0',           
            'licencia' => '1', 
            'so' => 'Windows 8.1 Pro',
            'tipo_so' => '32 Bits',
            'nombre_pc' => 'Soporte-PC',           
            'usuario_pc' => 'SOPORTE-HLB',
            'office' => '2010',
            'id_equipo' => 5
        ]);
        DetalleEquipo::create([
            'services_pack' => '1',           
            'licencia' => '0', 
            'so' => 'Windows XP Professional',
            'tipo_so' => '64 Bits',
            'nombre_pc' => 'UCI-PC',           
            'usuario_pc' => 'UCI-HLB',            
            'office' => '2016',
            'id_equipo' => 7
        ]);
    }
}
