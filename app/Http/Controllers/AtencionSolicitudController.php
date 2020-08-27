<?php

namespace App\Http\Controllers;

use App\Models\AtencionSolicitud;
use App\Models\Solicitud;
use App\Models\SolicitudEquipo;
use App\Models\Equipo;
use App\FirmasElectronicas;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class AtencionSolicitudController extends Controller
{
    public function crear_atencion_solicitud(Request $request){
        try{
            if (!(AtencionSolicitud::where('id_solicitud', '=', $request->id_solicitud)->exists())){
            $solicitud = new AtencionSolicitud();
            $solicitud->id_solicitud = $request->get('id_solicitud');
            $solicitud->fecha_atencion = Date('Y-m-d');
            $solicitud->hora_atencion = Date('H:i:s');
            $solicitud->observacion = $request->get('observacion');
            $solicitud->id_usuario = $request->get('id_usuario');
            $solicitud->save();
            for ( $i=0; $i<count($request->get('equipos')); $i++ ){
                $solicitud_equipo = new SolicitudEquipo();
                $solicitud_equipo->id_solicitud = $request->get('id_solicitud');
                $solicitud_equipo->id_equipo = $request->get('equipos')[$i];
                $solicitud_equipo->save();
            }
            $id_solicitud = $request->get('id_solicitud');
            Solicitud::Where("id_solicitud","=",$id_solicitud)->update([
                'estado' => $request->get('estado'), 
                'id_firma' => $request->get('id_imagen')
            ]);
            return response()->json(['log' => 'Registrado satisfactoriamente'], 200);  
            }else{
                Solicitud::Where("id_solicitud","=",$request->id_solicitud)->update([
                    'estado' => $request->get('estado'), 
                    'id_firma' => $request->get('id_imagen')
                ]);
                AtencionSolicitud::Where("id_solicitud","=",$request->id_solicitud)->update([
                    "observacion" => $request->get('observacion')
                ]);
                $equipos = $request->get('equipos');
                if (!empty($equipos)){ 
                    for ( $i=0; $i<count($request->get('equipos')); $i++ ){
                        $id_equipo = $request->get('equipos')[$i];
                        if((SolicitudEquipo::where('id_equipo', '=', $id_equipo)->where('id_solicitud', '=', $request->id_solicitud)->exists())){
                            $id = SolicitudEquipo::select('id_solicitud_equipo')->where('id_equipo', '=', $id_equipo)->where('id_solicitud', '=', $request->id_solicitud)->get()[0]["id_solicitud_equipo"];
                            SolicitudEquipo::find($id)->delete();
                            $solicitud_equipo = new SolicitudEquipo();
                            $solicitud_equipo->id_solicitud = $request->get('id_solicitud');
                            $solicitud_equipo->id_equipo = $request->get('equipos')[$i];
                            $solicitud_equipo->save();
                        }             
                    }
                }  
            }
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El registro que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);
        }    
    }


    public function uploadImages(Request $request)
    {
        $image = $request->file('image_name');
        $name = $request->file('image_name')->getClientOriginalName();
        $image_name = $request->file('image_name')->getRealPath();
        Cloudder::upload($image_name, null);
        list($width, $height) = getimagesize($image_name);
        $image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
        //save to uploads directory
        $image->move(public_path("uploads"), $name);
        //Save images
        $id = $this->saveImages($request, $image_url);
        Solicitud::Where("id_solicitud","=",$id_solicitud)->update(['id_firma' => $url]);
       return $id;
    }

    public function saveImages(Request $request, $image_url)
    {
        $image = new FirmasElectronicas();
        $image->image_name = $request->file('image_name')->getClientOriginalName();
        $image->image_url = $image_url;

        $image->save();
        return $image->id;
    }

    public function obtener_rutas(){
        $uploads = FirmasElectronicas::all();
        return response()->json($uploads);
    }

    public function info_atencion_solicitud_id($id, $cedula)
    {
        $atencion = AtencionSolicitud::select('atencion_solicitudes.observacion', 'firmas_electronicas.image_url')
        ->join('solicitudes', 'solicitudes.id_solicitud', '=', 'atencion_solicitudes.id_solicitud')
        ->join('firmas_electronicas', 'firmas_electronicas.id', '=', 'solicitudes.id_firma')
        ->where('atencion_solicitudes.id_solicitud', '=', $id)->get()[0];
        $lista = array();
        $equipos_involucrados = SolicitudEquipo::select('equipos.codigo')->join('equipos', 'equipos.id_equipo', '=', 'solicitud_equipos.id_equipo')
        ->where('solicitud_equipos.id_solicitud', '=', $id)->where('equipos.asignado', '=', $cedula)->get('equipos.codigo');
        for ( $j=0; $j<count($equipos_involucrados); $j++ ){
            array_push($lista, $equipos_involucrados[$j]["codigo"]);
        }
        $atencion["equipos"] = $lista;  
        return $atencion;
    }

    public function info_atencion_solicitud_edit($id)
    {
        $listado = array();
        if (AtencionSolicitud::where('id_solicitud', '=', $id)->exists()){
            $lista = array();
            $atencion = AtencionSolicitud::select('atencion_solicitudes.*','firmas_electronicas.image_url')
            ->leftjoin('solicitudes', 'solicitudes.id_solicitud', '=', 'atencion_solicitudes.id_solicitud')
            ->leftjoin('firmas_electronicas', 'firmas_electronicas.id', '=', 'solicitudes.id_firma')
            ->where('atencion_solicitudes.id_solicitud', '=', $id)->get()[0];

            $equipos_involucrados = SolicitudEquipo::where('solicitud_equipos.id_solicitud', '=', $id)->get("id_equipo");
            for ( $j=0; $j<count($equipos_involucrados); $j++ ){
                array_push($lista, $equipos_involucrados[$j]["id_equipo"]);
            }
            $listado = $atencion; 
            $listado["equipos"] = $lista; 
        }  
        return $listado;
    }

    public function editar_atencion_solicitud(Request $request)
    {        
        if (!(AtencionSolicitud::where('id_solicitud', '=', $request->id_solicitud)->exists())){
            $solicitud = new AtencionSolicitud();
            $solicitud->id_solicitud = $request->get('id_solicitud');
            $solicitud->fecha_atencion = Date('Y-m-d');
            $solicitud->hora_atencion = Date('H:i:s');
            $observacion = $request->get('observacion');
            if (!empty($observacion)){
                $solicitud->observacion = $request->get('observacion');
            }
            $solicitud->save();
            $equipos = $request->get('equipo');
            if (!empty($equipos)){ 
                for ( $i=0; $i<count($request->get('equipo')); $i++ ){
                    $id_equipo = $request->get('equipo')[$i];
                    if((SolicitudEquipo::where('id_equipo', '=', $id_equipo)->where('id_solicitud', '=', $request->id_solicitud)->exists())){
                        $id = SolicitudEquipo::select('id_solicitud_equipo')->where('id_equipo', '=', $id_equipo)->where('id_solicitud', '=', $request->id_solicitud)->get()[0]["id_solicitud_equipo"];
                        SolicitudEquipo::find($id)->delete();
                    }else{
                        $solicitud_equipo = new SolicitudEquipo();
                        $solicitud_equipo->id_solicitud = $request->get('id_solicitud');
                        $solicitud_equipo->id_equipo = $request->get('equipo')[$i];
                        $solicitud_equipo->save();
                    }             
                }
            }
        }else{
            AtencionSolicitud::Where("id_solicitud","=",$request->id_solicitud)->update([
                "observacion" => $request->get('observacion')
            ]);
            $por_guardar = array();
            $a_eliminar = array();
            $equipos = $request->get('equipo');
            $ids_equipos_seleccionados = array();
            $equipos_involucrados = SolicitudEquipo::select('id_equipo')->where('id_solicitud', '=', $request->id_solicitud)->get("id_equipo");
            for ($j = 0; $j < count($equipos_involucrados); $j++) {
                array_push($ids_equipos_seleccionados, $equipos_involucrados[$j]["id_equipo"]);
            }
            for ($i = 0; $i < count($equipos); $i++) {
                if ((!in_array($equipos[$i], $ids_equipos_seleccionados, true))) {
                    array_push($por_guardar, $equipos[$i]);
                }
            }
            for ($i = 0; $i < count($ids_equipos_seleccionados); $i++) {
                if (!in_array($ids_equipos_seleccionados[$i], $equipos, true)) {
                    array_push($a_eliminar, $ids_equipos_seleccionados[$i]);
                }
            }
            for ($i = 0; $i < count($por_guardar); $i++) {
                $solicitud_equipo = new SolicitudEquipo();
                $solicitud_equipo->id_equipo = $por_guardar[$i];
                $solicitud_equipo->id_solicitud = $request->id_solicitud;
                $solicitud_equipo->save();
            }
            for ($i = 0; $i < count($a_eliminar); $i++) {
                $id = SolicitudEquipo::select('id_solicitud_equipo')->where('id_equipo', '=', $a_eliminar[$i])->where('id_solicitud', '=', $request->id_solicitud)->get()[0]["id_solicitud_equipo"];
                SolicitudEquipo::find($id)->delete();
            }
        }
    }

    public function mostrar_codigo_equipos_solicitante($cedula)
    {
        return Equipo::select('id_equipo as id', 'codigo as dato')
            ->where('estado_operativo', '<>', 'B')
            ->where('asignado', '=', $cedula)
            ->get();
    }
}