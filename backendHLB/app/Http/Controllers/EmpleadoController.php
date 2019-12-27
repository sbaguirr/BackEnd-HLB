<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    
    public function mostrar_todos()
    {
        return Empleado::all();
    }

    public function buscar_por_nombre($nombreEmpleado)
    {
        return Empleado::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento','bspi_punto','correo')
        ->join('correos','correos.cedula','=','empleados.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->whereRaw('CONCAT(empleados.nombre," ",empleados.apellido) like ?',["%{$nombreEmpleado}%"])
        ->get();
    }

    public function buscar_por_punto($punto)
    {
        return Empleado::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento','bspi_punto','correo')
        ->join('correos','correos.cedula','=','empleados.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('bspi_punto',$punto)
        ->get();
    }

    public function buscar_por_departamento($departamento)
    {
        return Empleado::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento','bspi_punto','correo')
        ->join('correos','correos.cedula','=','empleados.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('departamentos.nombre',$departamento)
        ->get();
    }   
}
