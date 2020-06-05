<?php

use Illuminate\Database\Seeder;
use App\Models\Ip;
use App\Models\EstadoEquipo;

class IpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('ips')->delete();
        foreach(range(0, 10) as $x)
        {
            foreach(range(0, 9) as $y)
            {
                Ip::create([
                    'id_estado_equipo' => 1,
                    'direccion_ip' => '192.168.' . $x . '.' . $y,
                    'hostname' => '192.168.' . $x . '.' . $y,
                    'subred' => '192.168.' . $x . '.' . $y,
                    'fortigate' => '192.168.' . $x . '.' . $y,
                    'observacion' => 'OBSERVACION PRUEBA',
                    'maquinas_adicionales' => 0,
                    'nombre_usuario' => 'Samuel Braganza',
                    'encargado_registro' => 'admin',
                ]);   
            }
        }
    }
}
