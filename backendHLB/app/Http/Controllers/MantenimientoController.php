<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Carbon\Carbon;

class MantenimientoController extends Controller
{
    
    public function crear_mantenimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string',
            'tipo' => 'required',
            'fecha_inicio' => 'required'
        ]);
      //  $fecha_recordatorio= $request->get('fecha_recordatorio');
       // $hora_recordatorio= $request->get('hora_recordatorio');
        $fecha_inicio= $request->get('fecha_inicio');
        $fecha_fin= $request->get('fecha_fin'); //no puede ser menor que la fecha de inicio

       /*  if($fecha_fin!==""){
            $fecha_a= Carbon::createFromFormat('Y-m-d',$fecha_inicio,0,0,'America/Lima')->toDateTimeString();
            $fecha_b= Carbon::createFromFormat('Y-m-d',$fecha_fin,0,0,'America/Lima')->toDateTimeString();
            if($fecha_b->lessThan($fecha_a)){
                return response()->json(['log' => 'fecha fin no puede ser menor que la fecha de inicio '], 400);
            }
        } */

        if($validator->fails()){
            return response()->json(['log' => 'Debe completar los campos requeridos'], 400);
        }
        $mantenimiento= new Mantenimiento();
        $mantenimiento ->titulo=$request->get('titulo');
        $mantenimiento ->tipo=$request->get('tipo');
        $mantenimiento ->fecha_inicio=$fecha_inicio;
        $mantenimiento ->fecha_fin=$fecha_fin;
        $mantenimiento ->observacion_falla=$request->get('observacion_falla');
        $mantenimiento ->estado_fisico=$request->get('estado_fisico');
        $mantenimiento ->actividad_realizada=$request->get('actividad_realizada');
        $mantenimiento ->observacion=$request->get('observacion');
        $mantenimiento ->id_equipo=$request->get('id_equipo');
        $mantenimiento ->id_solicitud=$request->get('id_solicitud');
        $mantenimiento ->realizado_por=$request->get('realizado_por');
        $mantenimiento->save(); 
        /* 
        if($fecha_recordatorio!=="" && $hora_recordatorio!==""){ hoy y el futuro
        $recordatorio= new Recordatorio();    
        $recordatorio ->fecha= $fecha_recordatorio;
        $recordatorio ->hora= $hora_recordatorio;
        $recordatorio->id_mantenimiento= $mantenimiento->id_manteinimiento;
        $recordatorio->save();
        }
         */
     
    }

    public function editar_mantenimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string',
            'tipo' => 'required',
            'fecha_inicio' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['log' => 'Debe completar los campos requeridos'], 400);
        }
        $mantenimiento =Mantenimiento::find($request->get('id_mantenimiento')); 
        $fecha_inicio= $request->get('fecha_inicio');
        $fecha_fin= $request->get('fecha_fin'); //no puede ser menor que la fecha de inicio
        $mantenimiento ->titulo=$request->get('titulo');
        $mantenimiento ->tipo=$request->get('tipo');
        $mantenimiento ->fecha_inicio=$fecha_inicio;
        $mantenimiento ->fecha_fin=$fecha_fin;
        $mantenimiento ->observacion_falla=$request->get('observacion_falla');
        $mantenimiento ->estado_fisico=$request->get('estado_fisico');
        $mantenimiento ->actividad_realizada=$request->get('actividad_realizada');
        $mantenimiento ->observacion=$request->get('observacion');
        $mantenimiento ->id_equipo=$request->get('id_equipo');
        $mantenimiento ->id_solicitud=$request->get('id_solicitud');
        $mantenimiento ->realizado_por=$request->get('realizado_por');
        $mantenimiento->save(); 
    }

    public function mostrar_mantenimientos(Request $request){

        $codigo_equipo= $request->get("codigo_equipo");

    
        $query= Mantenimiento::select('mantenimientos.id_mantenimiento','equipos.codigo','mantenimientos.tipo',
        'titulo','correo','realizado_por','equipos.id_equipo')
        ->join('equipos','equipos.id_equipo','=','mantenimientos.id_equipo')
        ->where('equipos.codigo', $codigo_equipo);
        
        $itemSize = $query->count();
        $query->orderBy('mantenimientos.created_at', 'asc');
        $query= $query->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index")); 
        return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
    }
    

}