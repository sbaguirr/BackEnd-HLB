<?php

use Illuminate\Database\Seeder;
use App\Models\Tipo;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('routers')->delete();

        Tipo::create([
            'tipo' => 'impresora',
            'usa_ip' => 's'
        ]);Tipo::create([
            'tipo' => 'escaner',
            'usa_ip' => 's'
        ]);Tipo::create([
            'tipo' => 'mouse',
            'usa_ip' => 'n'
        ]);Tipo::create([
            'tipo' => 'teclado',
            'usa_ip' => 'n'
        ]);Tipo::create([
            'tipo' => 'pluma digital',
            'usa_ip' => 'n'
        ]);Tipo::create([
            'tipo' => 'ups',
            'usa_ip' => 'n'
        ]);Tipo::create([
            'tipo' => 'laptop',
            'usa_ip' => 's'
        ]);Tipo::create([
            'tipo' => 'cpu',
            'usa_ip' => 's'
        ]);Tipo::create([
            'tipo' => 'proyector',
            'usa_ip' => 'n'
        ]);Tipo::create([
            'tipo' => 'router',
            'usa_ip' => 's'
        ]);
    }
}
