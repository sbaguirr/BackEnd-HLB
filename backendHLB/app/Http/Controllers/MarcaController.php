<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Marca;

class MarcaController extends Controller
{
    public function listado_marcas()
    {
        return Marca::all();
    }

    public function crear_marca(Request $request){
        try{
            $marca= new Marca();
            $marca->nombre= $request->get('nombre');
            $marca->save();
            return response()->json(['log' => 'Marca almacenada satisfactoriamente'], 200); 
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'La marca que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);        
        }
    }

    public function editar_marca(Request $request){
        try{
            $marca = Marca::find($request->get('key')); 
            $marca->nombre= $request->get('nombre');
            $marca->save();
            return response()->json(['log' => 'Marca actualizada satisfactoriamente'], 200); 
         } catch(QueryException $e){
                $error_code = $e->errorInfo[1];
                if($error_code == 1062){
                    return response()->json(['log'=>'La marca que ha ingresado ya existe'],500);
                }
                return response()->json(['log'=>$e],500);        
            } 
    }

    public function filtrar_marcas(Request $request){

        $marca= $request->get("marca");
    
        $query= Marca::select('id_marca','nombre')
        ->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index"));

        if (!empty($marca)){
            $query= $query->whereRaw('nombre like ?',["%{$marca}%"]);
        }
        $itemSize = $query->count();
        $query->orderBy('nombre', 'asc');
        return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
    }

    public function marca_id($id){
        $marca = Marca::find($id); 
        return  $marca->nombre;
    }

    public function eliminar_marca($id_marca){
         try{
         $marca= Marca::find($id_marca)->delete();
         return response()->json(['log'=>'Registro eliminado satisfactoriamente'],200);
         }catch(Exception $e){
            return response()->json(['log'=>$e],400);
        }
    }
}