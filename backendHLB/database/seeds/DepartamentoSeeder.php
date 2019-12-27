<?php

use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departamentos')->delete();

        Departamento::create([
            'nombre' => 'Sistemas',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Finanzas',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Mantenimiento',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Administración',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Laboratorio',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Auditoría Interna',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'UCI',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Dietética',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Recepción',
            'id_organizacion' => 1
        ]);
        Departamento::create([
            'nombre' => 'Talento Humano',
            'id_organizacion' => 1
        ]);

        
        Departamento::create([
            'nombre' => 'Finanzas',
            'id_organizacion' => 2
        ]);
        Departamento::create([
            'nombre' => 'Administración',
            'id_organizacion' => 2
        ]);
        Departamento::create([
            'nombre' => 'Laboratorio',
            'id_organizacion' => 2
        ]);
        Departamento::create([
            'nombre' => 'Dietética',
            'id_organizacion' => 2
        ]);
        Departamento::create([
            'nombre' => 'Recepción',
            'id_organizacion' => 2
        ]);
        Departamento::create([
            'nombre' => 'Talento Humano',
            'id_organizacion' => 2
        ]);


        Departamento::create([
            'nombre' => 'Finanzas',
            'id_organizacion' => 3
        ]);
        Departamento::create([
            'nombre' => 'Mantenimiento',
            'id_organizacion' => 3
        ]);
        Departamento::create([
            'nombre' => 'Administración',
            'id_organizacion' => 3
        ]);
        Departamento::create([
            'nombre' => 'Recepción',
            'id_organizacion' => 3
        ]);
        Departamento::create([
            'nombre' => 'Talento Humano',
            'id_organizacion' => 3
        ]);


        Departamento::create([
            'nombre' => 'Finanzas',
            'id_organizacion' => 4
        ]);
        Departamento::create([
            'nombre' => 'Administración',
            'id_organizacion' => 4
        ]);
        Departamento::create([
            'nombre' => 'Recepción',
            'id_organizacion' => 4
        ]);
        Departamento::create([
            'nombre' => 'Talento Humano',
            'id_organizacion' => 4
        ]);
    }
}
