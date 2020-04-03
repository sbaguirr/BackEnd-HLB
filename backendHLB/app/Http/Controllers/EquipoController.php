<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\DetalleComponente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class EquipoController extends Controller
{
    const REQ = 'El campo :attribute no puede estar vacío';
    Const MAX = 'El campo :attribute supera la longitud maxima permitida';
   
    //creacion
    public function crear_comp_laptop(Request $request){
        DB::beginTransaction();
        try{
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'laptop';
            $computador->id_marca = $request->get('pc-id_marca');
            $computador->modelo = $request->get('pc-modelo');
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'OPERATIVO';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->numero_serie = $request->get('pc-numero_serie');
            $computador->save();

            $num_slots = new DetalleComponente();
            $num_slots->campo = 'numero_slots';
            $num_slots->dato = $request->get('pc-numero_slots');
            $num_slots->id_equipo = $computador->id_equipo;
            $num_slots->save();

            $ram_soport = new DetalleComponente();
            $ram_soport->campo = 'ram_soportada';
            $ram_soport->dato = $request->get('pc-ram_soportada');
            $ram_soport->id_equipo = $computador->id_equipo;
            $ram_soport->save();

            $num_slots = new DetalleComponente();
            $num_slots->campo = 'nucleos';
            $num_slots->dato = $request->get('pc-nucleos');
            $num_slots->id_equipo = $computador->id_equipo;
            $num_slots->save();

            $ram_soport = new DetalleComponente();
            $ram_soport->campo = 'frecuencia';
            $ram_soport->dato = $request->get('pc-frecuencia');
            $ram_soport->id_equipo = $computador->id_equipo;
            $ram_soport->save();


            foreach($request->except(['pc-codigo','pc-descripcion',"pc-numero_serie",'pc-id_marca','pc-modelo','pc-ram_soportada','pc-numero_slots']) as $clave => $valor){
                $comp = new Equipo();
                $comp->id_marca = $valor['id_marca'];
                $comp->codigo= $valor['codigo'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['numero_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion')?$valor['descripcion']:'';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'OPERATIVO';
                $comp->componente_principal = $computador->id_equipo;
                $comp->tipo_equipo = explode("_",explode('-',$clave)[1])[0]."_".explode("_",explode('-',$clave)[1])[1] ;
                $comp->save();

               if( Str::contains($clave,'disco_duro')||Str::contains($clave,'ram')){
                $tipo = new DetalleComponente();
                $tipo->campo = 'tipo';
                $tipo->dato = $valor['tipo'];
                $tipo->id_equipo = $comp->id_equipo;
                $tipo->save();

                $capacidad = new DetalleComponente();
                $capacidad->campo = 'capacidad';
                $capacidad->dato = $valor['capacidad'];
                $capacidad->id_equipo = $comp->id_equipo;
                $capacidad-> save();
               }
               if(Str::contains($clave,'procesador')){
                $num_slots = new DetalleComponente();
                $num_slots->campo = 'nucleos';
                $num_slots->dato = $valor['nucleos'];
                $num_slots->id_equipo = $comp->id_equipo;
                $num_slots->save();

                $ram_soport = new DetalleComponente();
                $ram_soport->campo = 'frecuencia';
                $ram_soport->dato = $valor['frecuencia'];
                $ram_soport->id_equipo = $comp->id_equipo;
                $ram_soport->save();
            }

               
                
            }
            
            DB::commit();
            return response()->json(['log'=>'exito'],200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log'=>$e],400);
        }
    }



    public function crear_Comp_Desktop(Request $request){
        
        $validator = self::validatorDesktop($request);
        if($validator!=null){
            return $validator;
        } 
        
        DB::beginTransaction();

        try{
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'desktop';
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'OPERATIVO';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->save();

            $cpu = new Equipo();
            $cpu->componente_principal = $computador->id_equipo;
            $cpu->fecha_registro = Date('Y-m-d H:i:s');
            $cpu->tipo_equipo = 'cpu';
            $cpu->encargado_registro = 'admin';
            $cpu->estado_operativo = 'OPERATIVO';
            $cpu->save();
          
            foreach($request->except(['pc-codigo','pc-descripcion']) as $clave => $valor) {
                
                $comp = new Equipo();
                $comp->codigo = $valor['codigo'];
                $comp->id_marca = $valor['id_marca'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['numero_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion')?$valor['descripcion']:'';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'OPERATIVO';
               
                if(Str::contains($clave,'pc')){
                    $comp->componente_principal = $computador->id_equipo;
                }
                if(Str::contains($clave,'cpu')){
                    $comp->componente_principal = $cpu->id_equipo;
                }
                $comp->tipo_equipo = Str::contains($clave,'_')? explode("_",explode('-',$clave)[1])[0]."_".explode("_",explode('-',$clave)[1])[1]:$clave ;
                $comp->save();

                if(Str::contains($clave,'disco_duro')||Str::contains($clave,'ram')){
                    $tipo = new DetalleComponente();
                    $tipo->campo = 'tipo';
                    $tipo->dato = $valor['tipo'];
                    $tipo->id_equipo = $comp->id_equipo;
                    $tipo->save();

                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'capacidad';
                    $capacidad->dato = $valor['capacidad'];
                    $capacidad->id_equipo = $comp->id_equipo;
                    $capacidad-> save();
                }

                if(Str::contains($clave,'procesador')){
                    $num_slots = new DetalleComponente();
                    $num_slots->campo = 'nucleos';
                    $num_slots->dato = $valor['nucleos'];
                    $num_slots->id_equipo = $comp->id_equipo;
                    $num_slots->save();

                    $ram_soport = new DetalleComponente();
                    $ram_soport->campo = 'frecuencia';
                    $ram_soport->dato = $valor['frecuencia'];
                    $ram_soport->id_equipo = $comp->id_equipo;
                    $ram_soport->save();
                }

                if(Str::contains($clave,'tarjeta_madre')){
                    $num_slots = new DetalleComponente();
                    $num_slots->campo = 'numero_slots';
                    $num_slots->dato = $valor['numero_slots'];
                    $num_slots->id_equipo = $comp->id_equipo;
                    $num_slots->save();

                    $ram_soport = new DetalleComponente();
                    $ram_soport->campo = 'ram_soportada';
                    $ram_soport->dato = $valor['ram_soportada'];
                    $ram_soport->id_equipo = $comp->id_equipo;
                    $ram_soport->save();

                    $disc_conect = new DetalleComponente();
                    $disc_conect->campo = 'disc_conect';
                    $disc_conect->dato = $valor['disc_conect'];
                    $disc_conect->id_equipo = $comp->id_equipo;
                    $disc_conect->save();
                }
                
            }

            DB::commit();
            return response()->json(['log'=>'exito'],200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log'=>$e],400);



        }

 
    }
    
    //validacion para creacion
    public function validatorDesktop(Request $request){
        if(count($request->all())<11){
            return  response()->json(['log' => [ 'Faltan Datos' ],"obj"=>$request->all(),"count"=>count($request->all())],400);
        }
        foreach($request->all() as $clave => $valor) {
            if(!Str::contains($clave, 'case') && !Str::contains($clave, 'pc-codigo')&& !Str::contains($clave, 'pc-descripcion')){
                $validator1 = Validator::make($request->get($clave), [
                    'id_marca' => 'required|max:255',
                    'modelo' => 'required|max:255',
                    'numero_serie' => 'required'
                ],[
                    'required' =>self::REQ ,
                    'max:255' => self::MAX
                ]);
                if ($validator1->fails()) {
                    return  response()->json(['log' => [ $clave, $validator1->errors()]],400);
                }
            }
            
        }
        $valid_cod = Validator::make($request->all(), [
            'pc-codigo' => 'required|max:255',
        ],[
            'required' => self::REQ,
            'max:255' => self::MAX
        ]);
        if ($valid_cod->fails()) {
            return  response()->json(['log' => [ $clave, $valid_cod->errors()]],400);
        }
        $validatorS = self::validatorDesktopS($request);
        if($validatorS!=null){
            return $validatorS;
        }
        
        return null;

    }

    public function validatorDesktopS(Request $request){
        foreach($request->all() as $clave => $valor) {
            if(Str::contains($clave,'memoria_ram')||Str::contains($clave, 'disco_duro')){
                $validator2 = Validator::make($request->get($clave), [
                    'capacidad' => 'required|max:255',
                    'tipo' => 'required|max:255'
                ],[
                    'required' => self::REQ,
                    'max:255' => self::MAX
                ]);
                if ($validator2->fails()) {
                    return  response()->json(['log' => [ $clave, $validator2->errors()]],400);
                }
            }
            if(Str::contains($clave,'tarjeta_madre')){
                $validator3 = Validator::make($request->get($clave), [
                    'ram_soportada' => 'required|max:255',
                    'numero_slots' => 'required|max:255'

                ],[
                    'required' =>self:: REQ,
                    'max:255' => self::MAX
                ]);
                if ($validator3->fails()) {
                    return  response()->json(['log' => [ $clave, $validator3->errors()]],400);
                }
            }
        }
        return null;
    }

  

    //consulta, filtrado y paginado
    public function getEquipos(Request $request){
        $result = Equipo::select("*")->where("tipo_equipo","=",$request->get("tipo"))->where("estado_operativo","=","Operativo")->orderBy('created_at', 'desc');
        if($request->get("codigo")!=null && $request->get("codigo")!=""){
            $result = $result->where('codigo','like',"%".$request->get("codigo")."%");
        }
        if($request->get("user")!=null && $request->get("user")!=""){
            $result = $result->where('encargado_registro','like',"%".$request->get("user")."%");
        }
        if($request->get("num_serie")!=null && $request->get("num_serie")!=""){
            $result = $result->where('numero_serie','like',"%".$request->get("num_serie")."%");
        }
        if($request->get("marca")!=null && $request->get("marca")!=""){
            $result = $result->where('id_marca','=',$request->get("marca"));
        }
        if($request->get("fecha_desde")!=null && $request->get("fecha_desde")!=""){
            $result = $result->where('fecha_registro','>=',$request->get("fecha_desde"));
        }
        if($request->get("fecha_hasta")!=null && $request->get("fecha_hasta")!=""){
            $result = $result->where('fecha_registro','<=',$request->get("fecha_hasta"));
        }
        $ItemSize = $result->count();
        $result = $result->limit($request->get("page_size"))->offset($request->get("page_size")*$request->get("page_index"));
        return response()->json(["result"=>$result->get(),"ItemSize"=>$ItemSize])->header("ItemSize",$ItemSize);
    }


    //consulta por ID
    public function getDesktopByID($idequipo){
        $ids=array();
        $res = DB::select(DB::raw("select * from equipos where id_equipo = ".$idequipo." or (componente_principal = ".$idequipo." or componente_principal = (select id_equipo from equipos where componente_principal = ".$idequipo." and tipo_equipo = 'cpu') );"));
        foreach($res as $obj){
            array_push($ids, $obj->id_equipo);
        }
        $comp = DetalleComponente::select("*")->whereIn("id_equipo",$ids);
        $response = self::generateDataDesktop(json_decode(json_encode($res), true),$comp->get()->toArray());
        return response()->json($response );
    }


    public function getLaptopByID($idequipo){
        $laptops= Equipo::Where("id_equipo","=",$idequipo)->orWhere("componente_principal","=",$idequipo);
        $compenentes = DetalleComponente::WhereIn("id_equipo",$laptops->get(['id_equipo']));  
        $var = self::generateDataLaptop($laptops->get()->toArray(),$compenentes->get()->toArray());
        return response()->json($var);
    }

    private function generateDataLaptop($equipos,$detalles){
        $laptop =  self::fil_obj($equipos,"tipo_equipo","laptop");
        $ram_soport = self::fil_obj($detalles,"campo","ram_soportada");
        $frecuencia = self::fil_obj($detalles,"campo","frecuencia");
        $nucleos = self::fil_obj($detalles,"campo","nucleos");
        $num_slots = self::fil_obj($detalles,"campo","numero_slots");
        $final = ["pc-codigo"=>$laptop["codigo"], "pc-id_marca"=>$laptop["id_marca"],
        "pc-modelo"=> $laptop["modelo"],
        "id_equipo"=>$laptop["id_equipo"],
        "pc-numero_serie"=>$laptop["numero_serie"],
        'pc-ram_soportada'=> $ram_soport["dato"],
        // 'pc-frecuencia'=>$frecuencia["dato"],
        // 'pc-nucleos'=>$nucleos["dato"],
        'pc-numero_slots'=>$num_slots["dato"],
        "pc-descripcion"=>$laptop["descripcion"],
        'id-ram_soportada'=> $ram_soport["id"],
        // 'id-frecuencia'=>$frecuencia["id"],
        // 'id-nucleos'=>$nucleos["id"],
        'id-numero_slots'=>$num_slots["id"],];
        return self::filtro_dinamico_plus($final,$equipos,$detalles,["pc-disco_duro","cpu-memoria_ram","pc-procesador"]);
    }


    private function generateDataDesktop($equipos,$detalles){
        $pc= self::fil_obj($equipos,"tipo_equipo","desktop");
        $final = ["pc-codigo"=>$pc["codigo"],"pc-descripcion"=>$pc["descripcion"],];
        return self::filtro_dinamico_plus($final,$equipos,$detalles,["cpu-disco_duro","cpu-memoria_ram",'pc-monitor', 'pc-teclado', 'pc-parlantes', 'pc-mouse','cpu-tarjeta_red', 'cpu-case', 'cpu-fuente_poder','cpu-tarjeta_madre', 'cpu-procesador']);
    }


    //funciones auxiliares para getLaptopByID
    private function fil_obj($array,$key,$value){
        return array_values(array_filter($array,function($obj) use ($key,$value){return $obj[$key]===$value;}))[0];
    }

    

    private function filtro_dinamico_plus($final,$equipos,$detalles,$claves){
        for($k=0;$k<count($claves);$k++){
            $arr = array_values(array_filter($equipos, function($obj) use($claves,$k){return Str::contains($obj["tipo_equipo"],explode('-',$claves[$k])[1]);}));
        $result = array();
        $val = Str::contains($claves[$k],"memoria_ram")||Str::contains($claves[$k],"disco_duro");
        if($val){
            $final= array_merge($final,[("num_".(Str::contains($claves[$k],"memoria_ram")?"memoria_ram":"disco_duro"))=>count($arr)]);
        }
        for ( $i=0;$i<count($arr);$i++){
            $detalle =array_values(array_filter($detalles, function($obj) use ($arr,$i){return $obj["id_equipo"]===$arr[$i]["id_equipo"];}));
            $element = ($arr[$i]);
            for( $j=0;$j<count($detalle);$j++){
                 $d = $detalle[$j];
                 $element = array_merge($element,[$d["campo"]=> $d["dato"],("id-".$d["campo"])=>$d["id"]]);
            }
            $obj_final = [$claves[$k].($val?("_".($i+1)):"")=>$element];
            $result = array_merge($result,$obj_final);
        }
        $final = array_merge($final,$result);
        }
        
        return $final;
    }

    
    public function deleteEquipoByID($idequipo){
        DB::beginTransaction();
        try{
            $res1 = Equipo::Where("id_equipo","=",$idequipo)->update(['estado_operativo' => "E"]);
            $res2 = Equipo::Where("componente_principal","=",$idequipo)->update(['componente_principal' => null]);
            DB::commit();
            return response()->json([$res1 ,$res2]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log'=>$e],400);
        }
    }


    public function getDetalleComp(Request $request){
        $value = DetalleComponente::select("*")->whereIn("id_equipo",$request)->get();    
        return response()->json( $value);
    }

    public function editDesktop(Request $request,$idequipo){
        DB::beginTransaction();
        try{
            Equipo::Where("id_equipo","=", $idequipo)->update(["codigo"=>$request->get("pc-codigo"),"descripcion"=>$request->get("pc-descripcion")]);
            self::editDeskAux($request,['pc-teclado'=>[], 'pc-parlantes'=>[], 'pc-mouse'=>[],'cpu-tarjeta_red'=>[], 'cpu-case'=>[], 'cpu-fuente_poder'=>[],'cpu-tarjeta_madre'=>['ram_soportada', 'numero_slots', 'disc_conect'], 'cpu-procesador'=>["frecuencia","nucleos"]]);
            self::editEquipoAux($request,"memoria_ram","cpu");
            self::editEquipoAux($request,"disco_duro","cpu");
            DB::commit();
            return response()->json(['log'=>'exito'],200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log'=>$e],400);
        }
    }

    private function editDeskAux($request,$arr){
        foreach($arr as $key => $val){
            Equipo::Where("id_equipo","=", $request->get($key)["id_equipo"])->update(["codigo"=>$request->get($key )["codigo"],"id_marca"=>$request->get($key )["id_marca"], "modelo"=>$request->get($key )["modelo"],"numero_serie"=>$request->get($key )["numero_serie"],"descripcion"=>$request->get($key )["descripcion"]]);
            for($i=0;$i<count($val);$i++){
                DetalleComponente::Where("id","=",$request->get($key)["id-".$val[$i]])->update(["dato"=>$request->get($key)[$val[$i]]]);
            }
        }

    }

    public function editLaptop(Request $request,$idequipo){
        DB::beginTransaction();
        try{
            $res1 = Equipo::Where("id_equipo","=", $idequipo)->update(["codigo"=>$request->get("pc-codigo"),"id_marca"=>$request->get("pc-id_marca"), "modelo"=>$request->get("pc-modelo"),"numero_serie"=>$request->get("pc-numero_serie"),"descripcion"=>$request->get("pc-descripcion")]);
            self::editDetAux( $request,["ram_soportada","numero_slots"]);
            self::editDeskAux($request,['pc-procesador'=>["frecuencia","nucleos"]]);
            self::editEquipoAux($request,"memoria_ram","cpu",$idequipo);
            self::editEquipoAux($request,"disco_duro","pc",$idequipo);
            DB::commit();
            return response()->json(['log'=>'exito'],200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log'=>$e],400);
        }
    }

    private function editDetAux($response,$arr){
        for($i = 0;$i<count($arr);$i++){
            $res = DetalleComponente::Where("id","=",$response->get("id-".$arr[$i]))->update(["dato"=>$response->get("pc-".$arr[$i])]);
        }
    }

    private function editEquipoAux($request,$eq,$p,$idequipo){
        for($i=0;$i<$request->get("num_".$eq);$i++){
            $nomb = $p."-".$eq."_".($i+1);
            if(!Arr::has($request->get($nomb),'id_equipo')){
                $comp = new Equipo();
                $comp->codigo = $request->get($nomb)['codigo'];
                $comp->id_marca = $request->get($nomb)['id_marca'];
                $comp->modelo = $request->get($nomb)['modelo'];
                $comp->numero_serie = $request->get($nomb)['numero_serie'];
                $comp->descripcion = Arr::has($request->get($nomb), 'descripcion')?$request->get($nomb)['descripcion']:'';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'OPERATIVO';
                $comp->componente_principal = $idequipo;
                $comp->tipo_equipo = $eq;
                $comp->save();
                $tipo = new DetalleComponente();
                $tipo->campo = 'tipo';
                $tipo->dato = $request->get($nomb)['tipo'];
                $tipo->id_equipo = $comp->id_equipo;
                $tipo->save();
                $capacidad = new DetalleComponente();
                $capacidad->campo = 'capacidad';
                $capacidad->dato = $request->get($nomb)['capacidad'];
                $capacidad->id_equipo = $comp->id_equipo;
                $capacidad-> save();
            }else{
                $res1 = Equipo::Where("id_equipo","=", $request->get($nomb))->update(["codigo"=>$request->get($nomb)['codigo'],"id_marca"=>$request->get($nomb)['id_marca'], "modelo"=>$request->get($nomb)['modelo'],"numero_serie"=>$request->get($nomb)['numero_serie'],"descripcion"=>$request->get($nomb)['descripcion']]);
                $res2 = DetalleComponente::Where("id","=",$request->get($nomb)['id-tipo'])->update(["dato"=>$request->get($nomb)['tipo']]);
                $res3 = DetalleComponente::Where("id","=",$request->get($nomb)['id-capacidad'])->update(["dato"=>$request->get($nomb)['capacidad']]);
            }
        }
    }

    
   
}
