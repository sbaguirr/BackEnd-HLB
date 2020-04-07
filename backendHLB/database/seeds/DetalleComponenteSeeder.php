<?php

use Illuminate\Database\Seeder;
use App\Models\DetalleComponente;

class DetalleComponenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('detalle_componentes')->delete();
        DetalleComponente::create([
            'dato' => '1 GB',
            'campo' => 'capacidad', 
            'id_equipo' => 25
        ]);
        DetalleComponente::create([
            'dato' => 'DDR3',
            'campo' => 'tipo', 
            'id_equipo' => 25
        ]);
        DetalleComponente::create([
            'dato' => '1 GB',
            'campo' => 'capacidad', 
            'id_equipo' => 26
        ]);
        DetalleComponente::create([
            'dato' => 'DDR2',
            'campo' => 'tipo', 
            'id_equipo' => 26
        ]);
        DetalleComponente::create([
            'dato' => '1 TB',
            'campo' => 'capacidad', 
            'id_equipo' => 27
        ]);
        DetalleComponente::create([
            'dato' => 'DDR3',
            'campo' => 'tipo', 
            'id_equipo' => 27
        ]);
        DetalleComponente::create([
            'dato' => '512 Mb',
            'campo' => 'capacidad', 
            'id_equipo' => 28
        ]);
        DetalleComponente::create([
            'dato' => 'DDR3',
            'campo' => 'tipo', 
            'id_equipo' => 28
        ]);
        DetalleComponente::create([
            'dato' => '1 GB',
            'campo' => 'capacidad', 
            'id_equipo' => 29
        ]);
        DetalleComponente::create([
            'dato' => 'DDR',
            'campo' => 'tipo', 
            'id_equipo' => 29
        ]);
        DetalleComponente::create([
            'dato' => '3 GB',
            'campo' => 'capacidad', 
            'id_equipo' => 30
        ]);
        DetalleComponente::create([
            'dato' => 'DDR',
            'campo' => 'tipo', 
            'id_equipo' => 30
        ]);
        DetalleComponente::create([
            'dato' => '2 GB',
            'campo' => 'capacidad', 
            'id_equipo' => 31
        ]);
        DetalleComponente::create([
            'dato' => 'DDR2',
            'campo' => 'tipo', 
            'id_equipo' => 31
        ]);
        DetalleComponente::create([
            'dato' => '12 GB',
            'campo' => 'ram_soportada', 
            'id_equipo' => 20
        ]);
        DetalleComponente::create([
            'dato' => '3',
            'campo' => 'numero_slots', 
            'id_equipo' => 20
        ]);
        DetalleComponente::create([
            'dato' => '3 GB',
            'campo' => 'ram_soportada', 
            'id_equipo' => 21
        ]);
        DetalleComponente::create([
            'dato' => '1',
            'campo' => 'numero_slots', 
            'id_equipo' => 21
        ]);
        DetalleComponente::create([
            'dato' => '16 GB',
            'campo' => 'ram_soportada', 
            'id_equipo' => 22
        ]);
        DetalleComponente::create([
            'dato' => '1',
            'campo' => 'numero_slots', 
            'id_equipo' => 22
        ]);
        DetalleComponente::create([
            'dato' => '1 TB',
            'campo' => 'ram_soportada', 
            'id_equipo' => 23
        ]);
        DetalleComponente::create([
            'dato' => '2',
            'campo' => 'numero_slots', 
            'id_equipo' => 23
        ]);
        DetalleComponente::create([
            'dato' => '12 GB',
            'campo' => 'ram_soportada', 
            'id_equipo' => 24
        ]);
        DetalleComponente::create([
            'dato' => '3',
            'campo' => 'numero_slots', 
            'id_equipo' => 24
        ]);
        DetalleComponente::create([
            'dato' => '4',
            'campo' => 'nucleos', 
            'id_equipo' => 32
        ]);
        DetalleComponente::create([
            'dato' => '3',
            'campo' => 'frecuencia', 
            'id_equipo' => 32
        ]);
        DetalleComponente::create([
            'dato' => '8',
            'campo' => 'nucleos', 
            'id_equipo' => 33
        ]);
        DetalleComponente::create([
            'dato' => '1',
            'campo' => 'frecuencia', 
            'id_equipo' => 33
        ]);
        DetalleComponente::create([
            'dato' => '2',
            'campo' => 'nucleos', 
            'id_equipo' => 34
        ]);
        DetalleComponente::create([
            'dato' => '1',
            'campo' => 'frecuencia', 
            'id_equipo' => 34
        ]);
        DetalleComponente::create([
            'dato' => '2',
            'campo' => 'nucleos', 
            'id_equipo' => 35
        ]);
        DetalleComponente::create([
            'dato' => '2',
            'campo' => 'frecuencia', 
            'id_equipo' => 35
        ]);
        DetalleComponente::create([
            'dato' => '4',
            'campo' => 'nucleos', 
            'id_equipo' => 36
        ]);
        DetalleComponente::create([
            'dato' => '3',
            'campo' => 'frecuencia', 
            'id_equipo' => 36
        ]);
    }
}
