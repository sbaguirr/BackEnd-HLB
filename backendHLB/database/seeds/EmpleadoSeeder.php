<?php

use Illuminate\Database\Seeder;
use App\Models\Empleado;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empleados')->delete();

        Empleado::create([
            'cedula' => '0584721925',
            'nombre' => 'Rafael',
            'apellido' => 'Alvarez',
            'id_departamento' => '1'
        ]);
        Empleado::create([
            'cedula' => '1358978564',
            'nombre' => 'Kevin',
            'apellido' => 'Guamanquispe',
            'id_departamento' => '1'
        ]);
        Empleado::create([
            'cedula' => '1328169874',
            'nombre' => 'Marco',
            'apellido' => 'Mendieta',
            'id_departamento' => '1'
        ]);
        Empleado::create([
            'cedula' => '1205478963',
            'nombre' => 'Darwin',
            'apellido' => 'Tomalá',
            'id_departamento' => '2'
        ]);
        Empleado::create([
            'cedula' => '0258463258',
            'nombre' => 'Victor',
            'apellido' => 'Toral',
            'id_departamento' => '2'
        ]);
        Empleado::create([
            'cedula' => '0784361981',
            'nombre' => 'Ricardo',
            'apellido' => 'Koening',
            'id_departamento' => '4'
        ]);
        Empleado::create([
            'cedula' => '1478523458',
            'nombre' => 'Patricia',
            'apellido' => 'Panchana',
            'id_departamento' => '4'
        ]);
        Empleado::create([
            'cedula' => '0325896347',
            'nombre' => 'Irma',
            'apellido' => 'Cazar',
            'id_departamento' => '5'
        ]);
        Empleado::create([
            'cedula' => '1630258746',
            'nombre' => 'Kathiuska',
            'apellido' => 'Quinde',
            'id_departamento' => '6'
        ]);
        Empleado::create([
            'cedula' => '1589635784',
            'nombre' => 'Mercy',
            'apellido' => 'Muñoz',
            'id_departamento' => '8'
        ]);
    }
}
