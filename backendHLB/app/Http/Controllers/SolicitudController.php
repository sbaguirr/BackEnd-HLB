<?php

namespace App\Http\Controllers;
use App\Events\Notificar;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use DateTime;

class SolicitudController extends Controller
{

    /**
     * Filtrado de solicitudes en aplicación móvil.
     * Versión preliminar... 
     * Falta tomar en consideración: Cuando se haga una nueva solicitud.
     */
    public function filtrar_solicitudes(Request $request){

        $estado= $request->get("estado");
        $filtro_estado= $request->get("filtro_estado");
        $fecha_realizacion= $request->get("fecha_realizacion");
        $prioridad= $request->get("prioridad");
        $query= Solicitud::selectRaw('*');       

        if(strcasecmp($estado, "O") == 0){
            $query= $query->where('solicitudes.estado',$filtro_estado);
            if (!empty($fecha_realizacion)){
                $query= $query->whereDate('solicitudes.fecha_realizacion',$fecha_realizacion);
            }
        }else{ 
            $query= $query->where('solicitudes.estado',$estado);
        }
        if (!empty($prioridad)){
            $query= $query->where('solicitudes.prioridad',$prioridad);
        }
        $itemSize = $query->count();
        $query= $query->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index")); 
        return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
    }

    /**
     * Contador de solicitudes pendientes.
     * Versión preliminar... 
     * Falta tomar en consideración: Cuando se haga una nueva solicitud.
     */
    public function contar_solicitudes(){
        return Solicitud::contar_pendientes();
    }

    public function crear_solicitud(Request $request){
        $solicitud = new Solicitud();
        $solicitud->id_usuario = $request->get('id_usuario');
        $solicitud->prioridad = $request->get('prioridad');
        $solicitud->tipo = $request->get('tipo');
        $solicitud->observacion = $request->get('observacion');
        $solicitud->estado = 'P';
        $solicitud->fecha_realizacion = Date('Y-m-d');
        $solicitud->hora_realizacion = Date('H:i:s');
        $solicitud->save();
        event(new Notificar($solicitud->id_usuario));  //Cuando se cree una solicitud, se genera el evento web.
        return response()->json($solicitud,200);
    }

    

    public function mostrar_solicitudes(){
        return Solicitud::SelectRaw('solicitudes.*, empleados.nombre, empleados.apellido')
        ->join('users', 'users.username', '=', 'solicitudes.id_usuario')
        ->join('empleados', 'empleados.cedula', '=', 'users.cedula')
        ->orderBy('solicitudes.created_at','desc')->get();
    }

    public function mostrar_solicitudes_user($id_user){
        return Solicitud::SelectRaw('solicitudes.*')
        ->where('solicitudes.id_usuario', '=', $id_user)
        ->orderBy('solicitudes.created_at','desc')->get();
    }

    



}
