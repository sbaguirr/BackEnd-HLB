<?php

namespace App\Http\Controllers;

use App\Models\ProgramaInstalado;
use App\Models\ProgramaEquipo;
use Illuminate\Http\Request;

class ProgramaInstaladoController extends Controller
{
    public function programas(){
        return ProgramaInstalado::all();;
    }

    public function filtrar_programas(Request $request){
        $editor= $request->get("editor");
        $query= ProgramaInstalado::select('*')
        ->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index"));
        if($editor != null && !empty($editor)){
            $query= $query->where('editor', $editor);
        }
        $itemSize = $query->count();
        $query->orderBy('codigo', 'asc');
        return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
    }

    public function buscar_programa($nombre){
        return ProgramaInstalado::select('*')
        ->where('nombre','like',"%".strtolower($nombre)."%")
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function buscar_programa_id($id){
        return ProgramaInstalado::select('*')
        ->where('id_programa', '=', $id)
        ->get()[0];
    }

    public function lista_programas_id(Request $request){
        // DB::beginTransaction();
        // try{
        $nuevos = array();
        $a_eliminar = array();
        // $office = ProgramaEquipo::select('id_programa')->where('id_equipo','=',94)->get("id_programa");
        // for ( $i=0; $i<count($office); $i++ ){
        //     array_push($lista, $office[$i]["id_programa"]);
        // }
        

        $office = $request->get("pc-version_office");
        // ProgramaEquipo::select('id_programa')->where('id_equipo','=',99)->get("id_programa");
        // for ( $i=0; $i<count($office); $i++ ){
        //     array_push($lista1, $office[$i]);
        // }
        $lista = array();
            $offic = ProgramaEquipo::select('id_programa')->where('id_equipo','=',99)->get("id_programa");
            for ( $j=0; $j<count($offic); $j++ ){
                array_push($lista, $offic[$j]["id_programa"]);
            }

            $id = ProgramaEquipo::select('id')->where('id_programa','=',$office[1])->where('id_equipo','=',99)->get()[0]["id"];
        for ( $i=0; $i<count($office); $i++ ){
            // array_push($l, $office[$i]);
                if ((!in_array($office[$i], $lista,true))) {
                // $id=1000;
                // }else{
                    // $id=19858964000; 
                    
                array_push($nuevos, $office[$i]);    
                // $id1 = ProgramaEquipo::select('id')->where('id_programa','=',$office[$i])->where('id_equipo','=',99)->get()[0]["id"];
                // ProgramaEquipo::find($id1)->delete();
                }
            }
        
        for ( $i=0; $i<count($lista); $i++ ){
            if (!in_array($lista[$i], $office,true)) {
                    // $id=1000;
                    
                    array_push($a_eliminar, $lista[$i]); 
                    // }else{
                    //     $id=19858964000; 
                           
                    // $id1 = ProgramaEquipo::select('id')->where('id_programa','=',$office[$i])->where('id_equipo','=',99)->get()[0]["id"];
                    // ProgramaEquipo::find($id1)->delete();
                    }
                // $programa = new ProgramaEquipo();
                // $programa->id_equipo = $id_equipo;
                // $programa->id_programa = $lista[$i];
                // $programa->fecha_instalacion = Date('Y-m-d H:i:s');
                // $programa->save();
            }
        
            for ($i=0; $i<count($nuevos); $i++){
                $programa = new ProgramaEquipo();
                $programa->id_equipo = 99;
                $programa->id_programa = $nuevos[$i];
                $programa->fecha_instalacion = Date('Y-m-d H:i:s');
                $programa->save();
            }
            for ($i=0; $i<count($a_eliminar); $i++){
                $id1 = ProgramaEquipo::select('id')->where('id_programa','=',$a_eliminar[$i])->where('id_equipo','=',99)->get()[0]["id"];
                ProgramaEquipo::find($id1)->delete();
            }
        // DB::commit();
            return response()->json(['log' => 'exito'], 200);
        // } catch (Exception $e) {
        //     DB::rollback();
        //     return response()->json(['log' => $e], 400);
        // }
    }

    public function editores_programa(){
        return ProgramaInstalado::select('editor')->whereNotNull('editor')->distinct()->get();
    }
    
    public function crear_programa(Request $request){
        try{
            $programa = new ProgramaInstalado();
            $programa->codigo = $request->get('codigo');
            $programa->nombre = $request->get('nombre');
            $programa->version = $request->get('version');
            $programa->editor = $request->get('editor');
            $programa->encargado_registro = $request->get('encargado_registro');
            $programa->observacion = $request->get('observacion');
            $programa->save();
            return response()->json(['log' => 'Programa registrado satisfactoriamente'], 200); 
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El programa ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);        
        }
    }

    public function editar_programa(Request $request){
        try{
            $programa = ProgramaInstalado::find($request->get('id_programa')); 
            $programa->nombre= $request->get('nombre');
            $programa->version= $request->get('version');
            $programa->editor= $request->get('editor');
            $programa->observacion= $request->get('observacion');
            $programa->save();
            return response()->json(['log' => 'Programa actualizado satisfactoriamente'], 200); 
         } catch(QueryException $e){
                $error_code = $e->errorInfo[1];
                if($error_code == 1062){
                    return response()->json(['log'=>'El programa ingresado ya existe'],500);
                }
                return response()->json(['log'=>$e],500);        
            } 
    }

    public function eliminar_programa($id_programa){
        try{
            $programa = ProgramaInstalado::find($id_programa)->delete();
            return response()->json(['log'=>'Registro eliminado satisfactoriamente'], 200);
        }catch(Exception $e){
           return response()->json(['log'=>$e], 400);
        }
    }
}