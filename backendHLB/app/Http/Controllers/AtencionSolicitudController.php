<?php

namespace App\Http\Controllers;

use App\Models\AtencionSolicitud;
use App\Models\Solicitud;
use App\Models\SolicitudEquipo;
use App\FirmasElectronicas;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class AtencionSolicitudController extends Controller
{
    // public function crear_atencion_solicitud(Request $request, Request $requestFirma){
    //     $soli = Equipo::find($request->id_solicitud); 
    //     $id_firma = uploadImages($requestFirma);
    //     $soli ->id_firma = $id_firma;
    //     $soli = save();
    //     $solicitud = new AtencionSolicitud();
    //     $solicitud->fecha_atencion = Date('Y-m-d');
    //     $solicitud->hora_atencion = Date('H:i:s');
    //     $solicitud->observacion = $request->get('observacion');
    //     $solicitud->id_solicitud = $request->get('id_solicitud');
    //     $solicitud->id_usuario = $request->get('id_usuario');
    //     $solicitud->save();
    //     for ( $i=0; $i<count($request->get('equipos')); $i++ ){
    //         $solicitud_equipo = new SolicitudEquipo;
    //         $solicitud_equipo->id_solicitud = $request->get('id_solicitud');
    //         $solicitud_equipo->id_equipo = $request->get('equipos')[$i];
    //         $solicitud_equipo->save();
    //     }
    //     $id_firma = uploadImages($requestFirma);

    //     return response()->json($solicitud,$solicitud_equipo, 200);
    // }

    public function crear_atencion_solicitud(Request $request){
        try{
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
            // $estado_solicitud = Solicitud::find($id_solicitud);
            // $estado_solicitud->estado = $request->get('estado');
            // $estado_solicitud = save();
            Solicitud::Where("id_solicitud","=",$id_solicitud)->update(['estado' => $request->get('estado'), 
            'id_firma' => $request->get('id_imagen')]);
            
         // Solicitud::Where("id_solicitud","=",$id_solicitud)->update(['estado' => "C"]);
            return response()->json(['log' => 'Registrado satisfactoriamente'], 200);  
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El registro que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);
        }    
    }


    public function uploadImages(Request $request){


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
       //return $image->image_url;
       return $image->id;
   }

   public function obtener_rutas(){
    $uploads = FirmasElectronicas::all();
    return response()->json($uploads);
}
}







        // $ids_programas_instalados = array();
        // $consulta_instalados = ProgramaEquipo::select('id_programa')->where('id_equipo','=',$request->key)->get("id_programa");
        // for ( $j=0; $j<count($consulta_instalados); $j++ ){
        //     array_push($ids_programas_instalados, $consulta_instalados[$j]["id_programa"]);
        // }