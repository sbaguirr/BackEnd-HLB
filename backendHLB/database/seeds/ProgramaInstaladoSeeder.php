<?php

use Illuminate\Database\Seeder;
use App\Models\ProgramaInstalado;

class ProgramaInstaladoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('programas_instalados')->delete();

        ProgramaInstalado::create([
            'nombre' => 'Microsoft Office 2010',
            'codigo' => 'RGRT-SERG-SFGT-SRGR',
            'observacion' => 'Última versión',
            'encargado_registro' => 'admin'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Microsoft Office 2013',
            'codigo' => 'TDF4-SFG3-KUTG-IOKU',
            'observacion' => 'Actualización',
            'encargado_registro' => 'analista'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'XAMPP',
            'codigo' => '16469469561651',
            'observacion' => 'Actualización',
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Team Viewer',
            'codigo' => '54DF-SDB5-ERG2-GHB5',
            'observacion' => 'Actualización',
            'encargado_registro' => 'soporte'
        ]);
    }
}
