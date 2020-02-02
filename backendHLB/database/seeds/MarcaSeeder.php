<?php

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marcas')->delete();

        Marca::create([
            'nombre' => 'Flame Xtratech'
        ]);Marca::create([
            'nombre' => 'LG'
        ]);Marca::create([
            'nombre' => 'Genius'
        ]);Marca::create([
            'nombre' => 'Omega'
        ]);Marca::create([
            'nombre' => 'HP'
        ]);Marca::create([
            'nombre' => 'Epson'
        ]);Marca::create([
            'nombre' => 'Ricoh'
        ]);Marca::create([
            'nombre' => 'Canon'
        ]);Marca::create([
            'nombre' => 'Cisco'
        ]);Marca::create([
            'nombre' => 'Asus'
        ]);Marca::create([
            'nombre' => 'D-Link'
        ]);Marca::create([
            'nombre' => 'Netgear'
        ]);Marca::create([
            'nombre' => 'Huawei'
        ]);Marca::create([
            'nombre' => 'Motorola'
        ]);
    }
}
