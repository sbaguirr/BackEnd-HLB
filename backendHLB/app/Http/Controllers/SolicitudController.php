<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;

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
        return Solicitud::select('id_solicitud')
        ->where('estado', 'P')
        ->get()
        ->count();
    }

}
