<?php

namespace App\Http\Controllers;

use App\Models\Correo;
use Illuminate\Http\Request;

class CorreoController extends Controller
{

    public function mostrar_todos()
    {
        return Correo::all();
    }

    public function crear_correo(Request $request)
    {
        $mail= new Correo();
        $mail->correo= $request->get('correo');
        $mail->contrasena= $request->get('contrasena');
        $mail->estado= $request->get('estado');
        $mail->cedula= $request->get('cedula');
        $mail->save();        
    }

    public function buscar_por_fecha($fecha_asignacion)
    {
        return Correo::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento','bspi_punto','correo')
        ->join('empleados','empleados.cedula','=','correos.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->whereDate('correos.created_at',$fecha_asignacion)
        ->get();
    }

    public function buscar_por_estado($estado)
    {
        return Empleado::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento','bspi_punto','correo')
        ->join('correos','correos.cedula','=','empleados.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('estado',$estado)
        ->get();
    }

}
