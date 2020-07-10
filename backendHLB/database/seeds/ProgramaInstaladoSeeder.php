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
            'codigo' => 'HLB-SW-001',
            'observacion' => 'Última versión',
            'encargado_registro' => 'admin'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Microsoft Office Standard 2013',
            'codigo' => 'HLB-SW-002',
            'version' => '1.0', 
            'editor' => 'Microsoft Corporation',
            'observacion' => '',
            'encargado_registro' => 'analista'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'XAMPP',
            'codigo' => 'HLB-SW-003',
            'version' => '7.3.9-0', 
            'editor' => 'Bitnami',
            'observacion' => 'Gestor de BD actual',
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Team Viewer 14',
            'codigo' => 'HLB-SW-004',
            'version' => '14.4.2669', 
            'editor' => 'TeamViewer',
            'observacion' => 'Seguimiento remoto',
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Mendeley Desktop',
            'codigo' => 'HLB-SW-005',
            'version' => '1.19.4', 
            'editor' => 'Mendeley Ltd.',
            'observacion' => 'Gestor de referencias',
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'McAfee LiveSafe',
            'codigo' => 'HLB-SW-006',
            'version' => '16.0 R25', 
            'editor' => 'McAfee, LLC.',
            'observacion' => 'Antivirus con licencia por un año',
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Microsoft Visual Studio Code (User)',
            'codigo' => 'HLB-SW-007',
            'version' => '1.46.1', 
            'editor' => 'Microsoft Corporation',
            'observacion' => 'Editor de código avanzado',
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Google Chrome',
            'codigo' => 'HLB-SW-008',
            'version' => '83.0.4103.116', 
            'editor' => 'Google LLC',
            'observacion' => null,
            'encargado_registro' => 'soporte'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'MySQL Server',
            'codigo' => 'HLB-SW-009',
            'version' => '8.0.16', 
            'editor' => 'Oracle Corporation',
            'observacion' => 'Servidor de BD',
            'encargado_registro' => 'admin'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Sublime Text 3',
            'codigo' => 'HLB-SW-010',
            'version' => null, 
            'editor' => 'Sublime HQ Pty Ltd',
            'observacion' => null,
            'encargado_registro' => 'admin'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'Git',
            'codigo' => 'HLB-SW-011',
            'version' => '2.21.0', 
            'editor' => 'The Git Development Community',
            'observacion' => 'Control de versiones',
            'encargado_registro' => 'analista'
        ]);
        ProgramaInstalado::create([
            'nombre' => 'WinRAR',
            'codigo' => 'HLB-SW-012',
            'version' => '5.71.0', 
            'editor' => 'win.rar GmbH',
            'observacion' => null,
            'encargado_registro' => 'analista'
        ]);
    }
}
