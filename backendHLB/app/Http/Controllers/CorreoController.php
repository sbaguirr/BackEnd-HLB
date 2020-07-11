<?php

namespace App\Http\Controllers;

use App\Models\Correo;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CorreoController extends Controller
{

     public function mostrar_correos()
    {
        return Correo::select('correos.id_correo','empleados.nombre','empleados.apellido','departamentos.nombre as departamento',
        'bspi_punto','correo','correos.estado','correos.created_at as asignacion', 'empleados.cedula', 'correos.contrasena')
        ->join('empleados','empleados.cedula','=','correos.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->orderBy('empleados.apellido', 'asc')
        ->get();
    } 

    public function crear_correo(Request $request)
    {
        try{
        $mail= new Correo();
        $mail->correo= $request->get('correo');
        $mail->contrasena= $request->get('contrasena');
        $mail->estado= "EU";
        $mail->cedula= $request->get('cedula');
        $mail->save();  
        return response()->json(['log' => 'Correo registrado satisfactoriamente'], 200);    
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El correo que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);
        }  
    }

    public function editar_correo(Request $request)
    {
        $mail = Correo::find($request->get('id')); #id del correo
        try{
        $mail->correo= $request->get('correo');
        $mail->contrasena= $request->get('contrasena');
        $mail->estado= $request->get('estado');
        $mail->save();  
        return response()->json(['log' => 'Correo actualizado satisfactoriamente'], 200);    
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El correo que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);
        }  
    }

    public function eliminar_correo($id_correo){
        $mail = Correo::find($id_correo); 
        $mail->estado= "I"; #Inactivo
        $mail->save(); 
    }


public function filtrar_correos(Request $request){

    $departamento= $request->get("departamento");
    $fecha_asignacion= $request->get("fecha");
    $estado= $request->get("estado");
    $empleado= $request->get("empleado");

    $query= Correo::select('correos.id_correo','empleados.nombre','empleados.apellido','departamentos.nombre as departamento',
    'bspi_punto','correo','correos.estado','correos.created_at as asignacion')
    ->join('empleados','empleados.cedula','=','correos.cedula')
    ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
    ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion');
    
    if($departamento!= "Todos" && !empty($departamento)){
        $query= $query->where('departamentos.nombre', $departamento);
    }

    if (!empty($fecha_asignacion)){
        $query= $query->whereDate('correos.created_at',$fecha_asignacion);
    }

    if (empty($estado)){ #Para cargar por defecto todos los correos en uso.
        $query= $query->where('correos.estado','<>','I');
    }else{ # Entra cuando se seleccione un estado como filtro.
        $query= $query->where('correos.estado',$estado);
    }
    if (!empty($empleado)){
        $query= $query->whereRaw('CONCAT(empleados.nombre," ",empleados.apellido) like ?',["%{$empleado}%"]);
    }
    $itemSize = $query->count();
    $query->orderBy('empleados.apellido', 'asc');
    $query= $query->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index")); 
    return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
}



    public function correo_id($id){
        return Correo::selectRaw('empleados.cedula, empleados.nombre, empleados.apellido ,
        departamentos.nombre as departamento , bspi_punto, correos.*')
        ->join('empleados','empleados.cedula','=','correos.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('correos.id_correo',$id)
        ->get();
    }

}
