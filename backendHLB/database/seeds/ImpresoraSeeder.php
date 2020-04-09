<?php

use Illuminate\Database\Seeder;
use App\Models\Impresora;

class ImpresoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('impresoras')->delete();

        Impresora::create([
            'tipo' => 'Multifuncional',
            'tinta' => 'Epson 664',
            'cartucho' => '4 colores',
            'toner' => 'Experte TN1050_2',
            'cinta' => 'Cinta',
            'id_equipo' => 8
        ]);
        Impresora::create([
            'tipo' => 'Matricial',
            'cinta' => 'Cinta',
            'id_equipo' => 9
        ]);
        Impresora::create([
            'tipo' => 'Brazalete',
            'tinta' => 'Negro',
            'rollo' => 'Brazalet ZERB',
            'id_equipo' => 10
        ]);
        Impresora::create([
            'tipo' => 'Escáner',
            'Rodillo' => 'LEX462',
            'id_equipo' => 12
        ]);
        Impresora::create([
            'tipo' => 'Escáner',
            'Rodillo' => 'LEX461',
            'id_equipo' => 11
        ]);
    }
}
