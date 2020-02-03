<?php

namespace App\Http\Controllers;

use App\Models\Correo;
use Illuminate\Http\Request;

class CorreoController extends Controller
{

    public function mostrar_correos()
    {
        return Correo::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento',
        'bspi_punto','correo','correos.estado','correos.created_at as asignacion')
        ->join('empleados','empleados.cedula','=','correos.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->orderBy('empleados.apellido', 'asc')
        ->get();
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

    public function filtrar_correos($departamento,$fecha_asignacion=null){
        $query= Correo::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento',
        'bspi_punto','correo','correos.estado','correos.created_at as asignacion')
        ->join('empleados','empleados.cedula','=','correos.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion');
        
        if($departamento != "Todos" && !empty($fecha_asignacion)){
            $query= $query->where([['correos.created_at', 'like', "${fecha_asignacion}%"],
                ['departamentos.nombre', '=', $departamento]]);
        }
        if ($departamento != "Todos" && empty($fecha_asignacion)){
            $query= $query->where('departamentos.nombre',$departamento);
        }
        if ($departamento == "Todos" && !empty($fecha_asignacion)){
            $query= $query->whereDate('correos.created_at',$fecha_asignacion);
        }
        return  $query->orderBy('empleados.apellido', 'asc')->get();
    }

   

}
