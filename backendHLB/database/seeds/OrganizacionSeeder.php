<?php

use Illuminate\Database\Seeder;
use App\Models\Organizacion;

class OrganizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizaciones')->delete();

        Organizacion::create([
            'bspi_punto' => 'Hospital León Becerra',
        ]);

        Organizacion::create([
            'bspi_punto' => 'Hogar Inés Chambers'
        ]);

        Organizacion::create([
            'bspi_punto' => 'Residencia Mercedes Begue',
        ]);

        Organizacion::create([
            'bspi_punto' => 'Unidad Educativa San José del Buen Pastor'
        ]);
    }
}
