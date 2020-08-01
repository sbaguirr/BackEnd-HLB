<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    
    public function mostrar_todos()
    {
        return Empleado::select('empleados.nombre','empleados.apellido','cedula as id')
        ->get();
    }

    public function buscar_empleado($nombreEmpleado)
    {
        return Empleado::select('empleados.cedula','departamentos.nombre as departamento','bspi_punto')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->whereRaw('CONCAT(empleados.nombre," ",empleados.apellido) like ?',["{$nombreEmpleado}"])  
        ->get();
    }

    public function empleados_sistemas(){
        return Empleado::select('empleados.nombre', 'empleados.apellido', 'empleados.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->where('departamentos.nombre','=','Sistemas')->get();
    }
}
