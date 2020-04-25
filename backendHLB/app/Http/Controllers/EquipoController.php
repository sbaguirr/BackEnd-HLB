<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Ip;
use App\Models\Marca;
use App\Models\Empleado;
use App\Models\Departamento;
use App\Models\Organizacion;
use App\Models\DetalleComponente;
use App\Models\DetalleEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use DateTime;

class EquipoController extends Controller
{
    const REQ = 'El campo :attribute no puede estar vacío';
    const MAX = 'El campo :attribute supera la longitud maxima permitida';

    //creacion
    public function crear_comp_laptop(Request $request)
    {

        
        $valid = self::validar_cod_serie_num($request);
        if($valid!=null){
            return $valid;
        } 

        DB::beginTransaction();
        try {
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = "laptop";
            $computador->id_marca = $request->get('pc-id_marca');
            $computador->modelo = $request->get('pc-modelo');
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'O';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->numero_serie = $request->get('pc-numero_serie');
            $computador->ip = $request->get("pc-ip_asignada");
            $computador->asignado=$request->get("pc-empleado_asignado");
            $computador->save();
            if($request->get("pc-ip_asignada")!=null && $request->get("pc-ip_asignada")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip_asignada"))->update(['estado' => "EU"]);
            }
            $num_slots = new DetalleComponente();
            $num_slots->campo = 'slots_ram';
            $num_slots->dato = $request->get('pc-slots_ram');
            $num_slots->id_equipo = $computador->id_equipo;
            $num_slots->save();

            $ram_soport = new DetalleComponente();
            $ram_soport->campo = 'ram_soportada';
            $ram_soport->dato = $request->get('pc-ram_soportada');
            $ram_soport->id_equipo = $computador->id_equipo;
            $ram_soport->save();

            $disc_conect = new DetalleComponente();
            $disc_conect->campo = 'conexiones_disco';
            $disc_conect->dato = $request->get('pc-conexiones_disco');
            $disc_conect->id_equipo = $computador->id_equipo;
            $disc_conect->save();

            $detEq = new DetalleEquipo();
            $detEq->nombre_pc=$request->get('pc-nombre_pc');
            $detEq->usuario_pc=$request->get('pc-usuario_pc');
            $detEq->so=$request->get('pc-sistema_operativo');
            $detEq->office=$request->get('pc-version_office');
            $detEq->tipo_so=$request->get('pc-tipo_sistema_operativo');
            $detEq->services_pack=$request->get('pc-service');
            $detEq->licencia=$request->get('pc-licencia');
            $detEq->id_equipo= $computador->id_equipo;
            $detEq->save();

            $arr_objs =["pc-ups_regulador",'pc-codigo','pc-descripcion',"pc-numero_serie",'pc-id_marca','pc-modelo','pc-ram_soportada',
            'pc-slots_ram',"pc-ip_asignada","pc-empleado_asignado",'pc-nombre_pc','pc-usuario_pc','pc-sistema_operativo',
            'pc-version_office','pc-tipo_sistema_operativo','pc-service','pc-licencia','pc-conexiones_disco',
            "list_serie","list_cod"];
            if(($request->get("pc-ups_regulador")["tipo_equipo"]) != null){
                unset($arr_objs[0]);
            } 
            foreach($request->except($arr_objs) as $clave => $valor){
                $comp = new Equipo();
                $comp->id_marca = $valor['id_marca'];
                $comp->codigo = $valor['codigo'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['numero_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion') ? $valor['descripcion'] : null;
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'O';
                $comp->asignado=$request->get("pc-empleado_asignado");
                $comp->componente_principal = $computador->id_equipo;
                $comp->tipo_equipo =( Str::contains($clave, '_') ? explode("_", explode('-', $clave)[1])[0] . "_" . explode("_", explode('-', $clave)[1])[1] : explode('-', $clave)[1]);
                //strtoupper
                if(Str::contains($clave, 'ups_regulador')){
                    $comp->tipo_equipo=($valor['tipo_equipo']);
                    //strtoupper
                }
                $comp->save();

                if (Str::contains($clave, 'disco_duro') || Str::contains($clave, 'ram')) {
                    $tipo = new DetalleComponente();
                    $tipo->campo = 'tipo';
                    $tipo->dato = $valor['tipo'];
                    $tipo->id_equipo = $comp->id_equipo;
                    $tipo->save();

                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'capacidad';
                    $capacidad->dato = $valor['capacidad'];
                    $capacidad->id_equipo = $comp->id_equipo;
                    $capacidad->save();

                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'tipo_capacidad';
                    $capacidad->dato = $valor['tipo_capacidad'];
                    $capacidad->id_equipo = $comp->id_equipo;
                    $capacidad->save();
                }
                if (Str::contains($clave, 'procesador')) {
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
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }



    public function crear_Comp_Desktop(Request $request)
    {


        $valid = self::validar_cod_serie_num($request);
        if($valid!=null){
            return $valid;
        } 

        DB::beginTransaction();

        try {
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'desktop';
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'O';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->ip = $request->get("pc-ip_asignada");
            $computador->asignado=$request->get("pc-empleado_asignado");
            $computador->save();
            if($request->get("pc-ip_asignada")!=null && $request->get("pc-ip_asignada")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip_asignada"))->update(['estado' => "EU"]);
            }

            // $cpu = new Equipo();
            // $cpu->componente_principal = $computador->id_equipo;
            // $cpu->fecha_registro = Date('Y-m-d H:i:s');
            // $cpu->tipo_equipo = 'CPU';
            // $cpu->codigo = ($request->get('pc-codigo'))."_CPU";
            // $cpu->encargado_registro = 'admin';
            // $cpu->asignado=$request->get("pc-empleado_asignado");
            // $cpu->estado_operativo = 'O';
            // $cpu->save();

            $detEq = new DetalleEquipo();
            $detEq->nombre_pc=$request->get('pc-nombre_pc');
            $detEq->usuario_pc=$request->get('pc-usuario_pc');
            $detEq->id_equipo= $computador->id_equipo;
            $detEq->so=$request->get('pc-sistema_operativo');
            $detEq->tipo_so=$request->get('pc-tipo_sistema_operativo');
            $detEq->office=$request->get('pc-version_office');
            $detEq->services_pack=$request->get('pc-service');
            $detEq->licencia=$request->get('pc-licencia');
            $detEq->save();

            $arr_objs = ["pc-ups_regulador",'pc-codigo','pc-descripcion',"pc-ip_asignada","pc-empleado_asignado",'pc-nombre_pc',
            'pc-usuario_pc','pc-sistema_operativo','pc-version_office','pc-tipo_sistema_operativo','pc-service',
            'pc-licencia',"list_cod","list_serie"];

            if($request->get("pc-ups_regulador")["tipo_equipo"]!=null){
                unset($arr_objs[0]);
            } 

          
            foreach($request->except($arr_objs) as $clave => $valor) {
                
                $comp = new Equipo();
                $comp->codigo = $valor['codigo'];
                $comp->id_marca = $valor['id_marca'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['numero_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion') ? $valor['descripcion'] : '';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'O';
                $comp->asignado=$request->get("pc-empleado_asignado");

                // if (Str::contains($clave, 'pc')) {
                $comp->componente_principal = $computador->id_equipo;
                // }
                // if (Str::contains($clave, 'cpu')) {
                //     $comp->componente_principal = $cpu->id_equipo;
                // }
                $comp->tipo_equipo = strtoupper(Str::contains($clave, '_') ? explode("_", explode('-', $clave)[1])[0] . "_" . explode("_", explode('-', $clave)[1])[1] : explode('-', $clave)[1]);
                if(Str::contains($clave, 'ups_regulador')){
                    $comp->tipo_equipo=strtoupper($valor['tipo_equipo']);
                }
                $comp->save();

                if (Str::contains($clave, 'disco_duro') || Str::contains($clave, 'ram')) {
                    $tipo = new DetalleComponente();
                    $tipo->campo = 'tipo';
                    $tipo->dato = $valor['tipo'];
                    $tipo->id_equipo = $comp->id_equipo;
                    $tipo->save();

                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'capacidad';
                    $capacidad->dato = $valor['capacidad'];
                    $capacidad->id_equipo = $comp->id_equipo;
                    $capacidad->save();

                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'tipo_capacidad';
                    $capacidad->dato = $valor['tipo_capacidad'];
                    $capacidad->id_equipo = $comp->id_equipo;
                    $capacidad->save();
                }

                if (Str::contains($clave, 'procesador')) {
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

                if (Str::contains($clave, 'tarjeta_madre')) {
                    $num_slots = new DetalleComponente();
                    $num_slots->campo = 'slots_ram';
                    $num_slots->dato = $valor['slots_ram'];
                    $num_slots->id_equipo = $comp->id_equipo;
                    $num_slots->save();

                    $ram_soport = new DetalleComponente();
                    $ram_soport->campo = 'ram_soportada';
                    $ram_soport->dato = $valor['ram_soportada'];
                    $ram_soport->id_equipo = $comp->id_equipo;
                    $ram_soport->save();

                    $disc_conect = new DetalleComponente();
                    $disc_conect->campo = 'conexiones_disco';
                    $disc_conect->dato = $valor['conexiones_disco'];
                    $disc_conect->id_equipo = $comp->id_equipo;
                    $disc_conect->save();
                }
                
            }

            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }

    public function validar_cod_serie_num($request){
        $codigo_rep = Equipo::WhereIn("codigo",$request->get("list_cod"))->get(["codigo"]);
        if(count( $codigo_rep)!=0){
            return response()->json(['log' => "El codigo ".($codigo_rep[0]->codigo)." ya esta registrado en la base de datos. Por favor revisar..."], 400);
        }
        $num_serie_rep = Equipo::WhereIn("numero_serie",$request->get("list_serie"))->get(["numero_serie"]);
        if(count( $num_serie_rep)!=0){
            return response()->json(['log' => "El Numero de Serie ".($num_serie_rep[0]->numero_serie)." ya esta registrado en la base de datos. Por favor revisar..."], 400);
        }
        return null;
    }
    



    //consulta, filtrado y paginado
    public function getEquipos(Request $request)
    {
        $result = Equipo::select("*")->where("tipo_equipo", "=", $request->get("tipo"))->where("estado_operativo", "<>", "B")->orderBy('created_at', 'desc');
        if ($request->get("codigo") != null && $request->get("codigo") != "") {
            $result = $result->where('codigo', 'like', "%" . $request->get("codigo") . "%");
        }
        if ($request->get("user") != null && $request->get("user") != "") {
            $result = $result->where('encargado_registro', 'like', "%" . $request->get("user") . "%");
        }
        if ($request->get("num_serie") != null && $request->get("num_serie") != "") {
            $result = $result->where('numero_serie', 'like', "%" . $request->get("num_serie") . "%");
        }
        if ($request->get("marca") != null && $request->get("marca") != "") {
            $result = $result->where('id_marca', '=', $request->get("marca"));
        }
        if ($request->get("fecha_desde") != null && $request->get("fecha_desde") != "") {
            $result = $result->where('fecha_registro', '>=', $request->get("fecha_desde"));
        }
        if ($request->get("fecha_hasta") != null && $request->get("fecha_hasta") != "") {
            $result = $result->where('fecha_registro', '<=', $request->get("fecha_hasta"));
        }
        $ItemSize = $result->count();
        $result = $result->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index"));
        return response()->json(["result" => $result->get(), "ItemSize" => $ItemSize])->header("ItemSize", $ItemSize);
    }


    //consulta por ID
    public function getDesktopByID($idequipo)
    {
        //$ids = array();
        // $res = DB::select(DB::raw("select * from equipos where id_equipo = " . $idequipo . " or (componente_principal = " . $idequipo . " or componente_principal = (select id_equipo from equipos where componente_principal = " . $idequipo . " and tipo_equipo = 'cpu') );"));
        // foreach ($res as $obj) {
        //     array_push($ids, $obj->id_equipo);
        // }
        //json_decode(json_encode($res), true)
        $res = Equipo::Where("id_equipo","=",$idequipo)->orWhere("componente_principal","=",$idequipo);
        $comp = DetalleComponente::select("*")->whereIn("id_equipo",$res->get(['id_equipo']));
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $response = self::generateDataDesktop($res->get()->toArray(),$comp->get()->toArray(),$detEq->toArray()[0]);
        return response()->json($response);
    }


    public function getLaptopByID($idequipo){
        $laptops= Equipo::Where("id_equipo","=",$idequipo)->orWhere("componente_principal","=",$idequipo);
        $compenentes = DetalleComponente::WhereIn("id_equipo",$laptops->get(['id_equipo']));  
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $var = self::generateDataLaptop($laptops->get()->toArray(),$compenentes->get()->toArray(),$detEq->toArray()[0]);
        return response()->json($var);
    }

    private function generateDataLaptop($equipos,$detalles,$detEq){
        $laptop =  self::fil_obj($equipos,"tipo_equipo","laptop");
        $ram_soport = self::fil_obj($detalles,"campo","ram_soportada");
        $num_slots = self::fil_obj($detalles,"campo","slots_ram");
        $conect_disc = self::fil_obj($detalles,"campo","conexiones_disco");
        $final = ["pc-codigo"=>$laptop["codigo"], "pc-id_marca"=>$laptop["id_marca"],
        "pc-modelo"=> $laptop["modelo"],
        "id_equipo"=>$laptop["id_equipo"],
        "pc-numero_serie"=>$laptop["numero_serie"],
        'pc-ram_soportada'=> $ram_soport["dato"],
        'pc-ip_asignada'=>$laptop["ip"],
        'pc-empleado_asignado'=>$laptop["asignado"],
        'pc-slots_ram'=>$num_slots["dato"],
        "pc-conexiones_disco"=>$conect_disc["dato"],
        "id-conexiones_disco"=>$conect_disc["id"],
        'id-slots_ram'=>$num_slots["id"],
        "pc-descripcion"=>$laptop["descripcion"],
        'id-ram_soportada'=> $ram_soport["id"],
        "pc-nombre_pc"=>$detEq["nombre_pc"],
        "pc-usuario_pc"=>$detEq["usuario_pc"],
        "pc-sistema_operativo"=>$detEq["so"],
        "pc-tipo_sistema_operativo"=>$detEq["tipo_so"],
        "pc-licencia"=>$detEq["licencia"]=="1", "pc-service"=>$detEq["services_pack"]=="1","pc-version_office"=>$detEq["office"],'id-slots_ram'=>$num_slots["id"],];
        return self::filtro_dinamico_plus($final,$equipos,$detalles,["cpu-disco_duro","cpu-memoria_ram","pc-procesador","pc-ups_regulador"]);
    }


    private function generateDataDesktop($equipos,$detalles,$detEq){
        $pc= self::fil_obj($equipos,"tipo_equipo","desktop");
        $final = ["pc-codigo"=>$pc["codigo"],"pc-descripcion"=>$pc["descripcion"],
        'pc-ip_asignada'=>$pc["ip"],'pc-empleado_asignado'=>$pc["asignado"],"pc-nombre_pc"=>$detEq["nombre_pc"],"pc-usuario_pc"=>$detEq["usuario_pc"],"pc-sistema_operativo"=>$detEq["so"],"pc-tipo_sistema_operativo"=>$detEq["tipo_so"],"pc-licencia"=>$detEq["licencia"]=="1","pc-service"=>$detEq["services_pack"]=="1","pc-version_office"=>$detEq["office"],];
        return self::filtro_dinamico_plus($final,$equipos,$detalles,["cpu-disco_duro","cpu-memoria_ram",'pc-monitor', 'pc-teclado', 'pc-parlantes', 'pc-mouse','cpu-tarjeta_red', 'cpu-case', 'cpu-fuente_poder','cpu-tarjeta_madre', 'cpu-procesador',"pc-ups_regulador"]);
    }


    //funciones auxiliares para getLaptopByID
    private function fil_obj($array, $key, $value)
    {
        return array_values(array_filter($array, function ($obj) use ($key, $value) {
            return strtolower($obj[$key]) === $value;
        }))[0];
    }


    private function filtro_dinamico_plus($final,$equipos,$detalles,$claves){
        for($k=0; $k<count($claves); $k++){
            $arr = array_values(array_filter($equipos, function($obj) use($claves,$k){return Str::contains(explode('-',$claves[$k])[1],strtolower($obj["tipo_equipo"]));}));
            $result = array();
            $val = Str::contains($claves[$k],"memoria_ram")||Str::contains($claves[$k],"disco_duro");
            if($val){
                $final= array_merge($final,[("num_".(Str::contains($claves[$k],"memoria_ram")?"memoria_ram":"disco_duro"))=>count($arr)]);
            }
            for ( $i=0;$i<count($arr);$i++){
                $detalle =array_values(array_filter($detalles, function($obj) use ($arr,$i){return $obj["id_equipo"]===$arr[$i]["id_equipo"];}));
                $element = ($arr[$i]);
                for($j=0;$j<count($detalle); $j++){
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


    public function deleteEquipoByID($idequipo,$tipo)
    {
        DB::beginTransaction();
        try {
            //si el equipo que se da de baja tiene una ip asignada, se libera la ip
            $ip_old=Equipo::Where("id_equipo","=", $idequipo)->get(["ip"]);
            if(count($ip_old)!=0){
                Ip::Where("id_ip","=",$ip_old)->update(['estado' => "L"]);
            }
            $res1 = Equipo::Where("id_equipo", "=", $idequipo)->update(['estado_operativo' => "B"]);
            $res2 = Equipo::Where("componente_principal", "=", $idequipo);
            if($tipo=="laptop"){
                //cuando es una laptop el procesador no puede volver a ser asignado (esta soldado al equipo), se le da de baja con el equipo -- no estoy seguro :"v
                $res2 = $res2->where("tipo_equipo","<>","procesador");
                Equipo::Where("componente_principal", "=", $idequipo)->where("tipo_equipo","=","procesador")->update(['estado_operativo' => "B"]);
            }
            $res2=$res2->update(['componente_principal' => null]);
            DB::commit();
            return response()->json([$res1, $res2]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }
    



    public function editDesktop(Request $request, $idequipo)
    {
        DB::beginTransaction();
        try{
            $ip_old=Equipo::Where("id_equipo","=", $idequipo)->get(["ip"]);
            if(count($ip_old)!=0){
                Ip::Where("id_ip","=",$ip_old)->update(['estado' => "L"]);
            }
            Equipo::Where("id_equipo","=", $idequipo)->update(["codigo"=>$request->get("pc-codigo"),"descripcion"=>$request->get("pc-descripcion"),"ip"=>$request->get("pc-ip_asignada"),"asignado"=>$request->get("pc-empleado_asignado")]);
            if($request->get("pc-ip_asignada")!=null && $request->get("pc-ip_asignada")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip_asignada"))->update(['estado' => "EU"]);
            
            }
            DetalleEquipo::Where("id_equipo","=",$idequipo)->update(["usuario_pc"=>$request->get("pc-usuario_pc"),"nombre_pc"=>$request->get("pc-nombre_pc"), "so"=>$request->get('pc-sistema_operativo'),"tipo_so"=>$request->get('pc-tipo_sistema_operativo'),"services_pack"=>$request->get('pc-service'),"licencia"=>$request->get('pc-licencia')]);
            $arr_up = ['pc-teclado'=>[], 'pc-parlantes'=>[], 'pc-mouse'=>[],'cpu-tarjeta_red'=>[], 'cpu-case'=>[], 'cpu-fuente_poder'=>[],'cpu-tarjeta_madre'=>['ram_soportada', 'slots_ram', 'conexiones_disco'], 'cpu-procesador'=>["frecuencia","nucleos"]];
            if($request->get("pc-ups_regulador")["tipo_equipo"]!=null){
                $arr_up = array_merge($arr_up,["pc-ups_regulador"=>[]]);
            }
            self::editDeskAux($request,$arr_up);
            self::editEquipoAux($request,"memoria_ram","cpu",$idequipo);
            self::editEquipoAux($request,"disco_duro","cpu",$idequipo);
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }

    private function editDeskAux($request, $arr)
    {
        foreach ($arr as $key => $val) {
            Equipo::Where("id_equipo", "=", $request->get($key)["id_equipo"])->update(["codigo" => $request->get($key)["codigo"], "id_marca" => $request->get($key)["id_marca"], "modelo" => $request->get($key)["modelo"], "numero_serie" => $request->get($key)["numero_serie"], "descripcion" => $request->get($key)["descripcion"],"asignado"=>$request->get("pc-empleado_asignado")]);
            for ($i = 0; $i < count($val); $i++) {
                DetalleComponente::Where("id", "=", $request->get($key)["id-" . $val[$i]])->update(["dato" => $request->get($key)[$val[$i]]]);
            }
        }
    }

    public function editLaptop(Request $request, $idequipo)
    {
        DB::beginTransaction();
        try{
            $ip_old=Equipo::Where("id_equipo","=", $idequipo)->get(["ip"]);
            if(count($ip_old)!=0){
                Ip::Where("id_ip","=",$ip_old)->update(['estado' => "L"]);
            }
            Equipo::Where("id_equipo","=", $idequipo)->update(["codigo"=>$request->get("pc-codigo"),"id_marca"=>$request->get("pc-id_marca"), "modelo"=>$request->get("pc-modelo"),"numero_serie"=>$request->get("pc-numero_serie"),"descripcion"=>$request->get("pc-descripcion")]);
            if($request->get("pc-ip_asignada")!=null && $request->get("pc-ip_asignada")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip_asignada"))->update(['estado' => "EU"]);
            }
            DetalleEquipo::Where("id_equipo","=",$idequipo)->update(["usuario_pc"=>$request->get("pc-usuario_pc"),"nombre_pc"=>$request->get("pc-nombre_pc"), "so"=>$request->get('pc-sistema_operativo'),
            "tipo_so"=>$request->get('pc-tipo_sistema_operativo'),
           "services_pack"=>$request->get('pc-service'),
           "licencia"=>$request->get('pc-licencia')]);
            self::editDetAux( $request,["ram_soportada","slots_ram","conexiones_disco"]);
            $arr_up = ['pc-procesador'=>["frecuencia","nucleos"]];
            if($request->get("pc-ups_regulador")["tipo_equipo"]!=null){
                $arr_up = array_merge($arr_up,["pc-ups_regulador"=>[]]);
            }
            self::editDeskAux($request,$arr_a_up);
            self::editEquipoAux($request,"memoria_ram","cpu",$idequipo);
            self::editEquipoAux($request,"disco_duro","cpu",$idequipo);
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }

    private function editDetAux($response,$arr){
        for($i = 0;$i<count($arr);$i++){
             DetalleComponente::Where("id","=",$response->get("id-".$arr[$i]))->update(["dato"=>$response->get("pc-".$arr[$i])]);
        }
    }

    private function editEquipoAux($request, $eq, $p, $idequipo)
    {
        for ($i = 0; $i < $request->get("num_" . $eq); $i++) {
            $nomb = $p . "-" . $eq . "_" . ($i + 1);
            if (!Arr::has($request->get($nomb), 'id_equipo')) {
                $comp = new Equipo();
                $comp->codigo = $request->get($nomb)['codigo'];
                $comp->id_marca = $request->get($nomb)['id_marca'];
                $comp->modelo = $request->get($nomb)['modelo'];
                $comp->numero_serie = $request->get($nomb)['numero_serie'];
                $comp->descripcion = Arr::has($request->get($nomb), 'descripcion') ? $request->get($nomb)['descripcion'] : '';
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
                Equipo::Where("id_equipo","=", $request->get($nomb))->update(["codigo"=>$request->get($nomb)['codigo'],"id_marca"=>$request->get($nomb)['id_marca'], "modelo"=>$request->get($nomb)['modelo'],"numero_serie"=>$request->get($nomb)['numero_serie'],"descripcion"=>$request->get($nomb)['descripcion'],"asignado"=>$request->get("pc-empleado_asignado")]);
                DetalleComponente::Where("id","=",$request->get($nomb)['id-tipo'])->update(["dato"=>$request->get($nomb)['tipo']]);
                DetalleComponente::Where("id","=",$request->get($nomb)['id-capacidad'])->update(["dato"=>$request->get($nomb)['capacidad']]);
            }
        }
    }



    /*Muestra el código de los equipos que pueden ser un componente principal*/
    public function mostrar_codigos()
    {
        return Equipo::select('id_equipo as id','codigo as dato')
        ->where('estado_operativo','<>','B')
        ->get();
    }
    
    /*Listar computadoras y laptops Web Version*/
    public function darDeBajaEquipoID($idequipo,$tipo)
    {
        DB::beginTransaction();
        try {
            $res1 = Equipo::Where("id_equipo", "=", $idequipo)->update(['estado_operativo' => "B"]);
            $res2 = Equipo::Where("componente_principal", "=", $idequipo);
            if($tipo=="laptop"){
                $res2 = $res2->where("tipo_equipo","<>","procesador");
                Equipo::Where("componente_principal", "=", $idequipo)->where("tipo_equipo","=","procesador")->update(['estado_operativo' => "B"]);
            }
            $res2=$res2->update(['componente_principal' => null]);
            DB::commit();
            return response()->json([$res1, $res2]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }
    public function listar_laptops(){
        $final = array();
        $eq = Equipo::select('id_equipo')->where('tipo_equipo','=','laptop')->where('estado_operativo', '<>', 'B')->get();
        for ( $i=0; $i<count($eq); $i++ ){
            $varr = self::obtenerInfoLaptop($eq[$i]["id_equipo"]);
            array_push($final, $varr);
        }
        return ($final);
    }

    public function obtenerInfoLaptop($idequipo){
        $laptops= Equipo::Where("id_equipo","=",$idequipo)->orWhere("componente_principal","=",$idequipo);
        $marca = Marca::WhereIn("id_marca",$laptops->get(['id_marca']));
        $empleado = Empleado::WhereIn("cedula",$laptops->get(['asignado']));
        $dpto = Departamento::WhereIn("id_departamento",$empleado->get(['id_departamento']));
        $punto = Organizacion::WhereIn("id_organizacion",$dpto->get(['id_organizacion']));
        $compenentes = DetalleComponente::WhereIn("id_equipo",$laptops->get(['id_equipo']));  
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $var = self::generarDetalleLaptop($laptops->get()->toArray(),$compenentes->get()->toArray(),$detEq,
        $empleado->get()->toArray(), $dpto->get()->toArray(), $punto->get()->toArray(),$marca->get()->toArray());
        return response()->json($var);
    }

    private function generarDetalleLaptop($equipos, $detalles, $detEq, $empleado, $dpto, $punto, $marca)
    {
        $rams = array();
        $discos = array();
        $procesador = array();
        for ( $i=0; $i<count($equipos); $i++ ){
            $marca = Marca::Where("id_marca","=",$equipos[$i]["id_marca"])->get();
            $equipos[$i]['marca'] = $marca['0']["nombre"];
            if ($equipos[$i]["tipo_equipo"] === "memoria_ram" || $equipos[$i]["tipo_equipo"] === "disco_duro"){
                $capacidad = self::fil_obj($detalles,"campo","capacidad");
                $tipo = self::fil_obj($detalles,"campo","tipo");
                $equipos[$i]['capacidad'] = $capacidad["dato"];                
                $equipos[$i]['tipo'] = $tipo["dato"];
                if ($equipos[$i]["tipo_equipo"] === "memoria_ram"){
                    array_push($rams, $equipos[$i]);
                }elseif ($equipos[$i]["tipo_equipo"] === "disco_duro"){
                    array_push($discos, $equipos[$i]);
                }
            }elseif ($equipos[$i]["tipo_equipo"] === "procesador"){
                $frecuencia = self::fil_obj($detalles,"campo","frecuencia");
                $nucleos = self::fil_obj($detalles,"campo","nucleos");
                $equipos[$i]['frecuencia'] = $frecuencia["dato"];                
                $equipos[$i]['nucleos'] = $nucleos["dato"];
                array_push($procesador, $equipos[$i]);
            }
        }
        $laptop =  self::fil_obj($equipos,"tipo_equipo","laptop");
        $laptop["marca"] = $marca['0']['nombre'];
        $ram_soport = self::fil_obj($detalles,"campo","ram_soportada");
        $num_slots = self::fil_obj($detalles,"campo","numero_slots");
        if($empleado !== []){
            $laptop["empleado"] = $empleado['0']["nombre"];            
            $laptop["apellido"] = $empleado['0']["apellido"];
            $laptop["departamento"] = $dpto['0']["nombre"];
            $laptop["bspi"] = $punto['0']["bspi_punto"];
        };
        $final = [ "ram_soportada" => $ram_soport["dato"], "numero_slots" => $num_slots["dato"], "general" => $laptop, 
                   "so" => $detEq['0'], "procesador" => $procesador[0], "rams" => $rams, "discos" => $discos ];
        return $final;
    }
    
    /*************************************************** */
    public function listar_desktops(){
        $final = array();
        $eq = Equipo::select('id_equipo')->where('tipo_equipo','=','desktop')->where('estado_operativo', '<>', 'B')->get();
        for ( $i=0; $i<count($eq); $i++ ){
            $varr = self::obtenerInfoDesktop($eq[$i]["id_equipo"]);
            array_push($final, $varr);
        }
        return ($final);
    }

    public function obtenerInfoDesktop($idequipo){
        $laptops= Equipo::Where("id_equipo","=",$idequipo)->orWhere("componente_principal","=",$idequipo);
        $marca = Marca::WhereIn("id_marca",$laptops->get(['id_marca']));
        $empleado = Empleado::WhereIn("cedula",$laptops->get(['asignado']));
        $dpto = Departamento::WhereIn("id_departamento",$empleado->get(['id_departamento']));
        $punto = Organizacion::WhereIn("id_organizacion",$dpto->get(['id_organizacion']));
        $compenentes = DetalleComponente::WhereIn("id_equipo",$laptops->get(['id_equipo']));  
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $var = self::generarDetalleDesktop($laptops->get()->toArray(),$compenentes->get()->toArray(),$detEq,
        $empleado->get()->toArray(), $dpto->get()->toArray(), $punto->get()->toArray(),$marca->get()->toArray());
        return response()->json($var);
    }

    private function generarDetalleDesktop($equipos, $detalles, $detEq, $empleado, $dpto, $punto, $marca)
    {
        $rams = array();
        $discos = array();
        $fuente_alimentacion = array();
        for ( $i=0; $i<count($equipos); $i++ ){
            $marca = Marca::Where("id_marca","=",$equipos[$i]["id_marca"])->get();
            $equipos[$i]['marca'] = $marca['0']["nombre"];
            if ($equipos[$i]["tipo_equipo"] === "memoria_ram" || $equipos[$i]["tipo_equipo"] === "disco_duro"){
                $capacidad = self::fil_obj($detalles,"campo","capacidad");
                $tipo = self::fil_obj($detalles,"campo","tipo");
                $equipos[$i]['capacidad'] = $capacidad["dato"];                
                $equipos[$i]['tipo'] = $tipo["dato"];
                if ($equipos[$i]["tipo_equipo"] === "memoria_ram"){
                    array_push($rams, $equipos[$i]);
                }elseif ($equipos[$i]["tipo_equipo"] === "disco_duro"){
                    array_push($discos, $equipos[$i]);
                }
            }elseif ($equipos[$i]["tipo_equipo"] === "ups"){
                array_push($fuente_alimentacion, $equipos[$i]);
            }elseif ($equipos[$i]["tipo_equipo"] === "regulador"){
                array_push($fuente_alimentacion, $equipos[$i]);
            }
        }
        $laptop =  self::fil_obj($equipos,"tipo_equipo","desktop");
        $laptop["marca"] = $marca['0']['nombre'];
        if($empleado !== []){
            $laptop["empleado"] = $empleado['0']["nombre"];            
            $laptop["apellido"] = $empleado['0']["apellido"];
            $laptop["departamento"] = $dpto['0']["nombre"];
            $laptop["bspi"] = $punto['0']["bspi_punto"];
        };
        $final = [ "general" => $laptop, "so" => $detEq['0'], "rams" => $rams, "discos" => $discos];
        if($fuente_alimentacion !== []){ $final['f_alim'] = $fuente_alimentacion[0]; }
         $obj = self::filtro_dinamico_plus($final, $equipos, $detalles, ['pc-monitor', 'pc-teclado', 'pc-parlantes', 'pc-mouse',
         'cpu-tarjeta_red', 'cpu-case', 'cpu-fuente_poder','cpu-tarjeta_madre', 'cpu-procesador']);    
        $final['monitor'] = $obj['pc-monitor'];
        $final['teclado'] = $obj['pc-teclado'];
        $final['mouse'] = $obj['pc-mouse'];
        $final['parlantes'] = $obj['pc-parlantes'];
        $final['tarjeta_red'] = $obj['cpu-tarjeta_red'];
        $final['tarjeta_madre'] = $obj['cpu-tarjeta_madre'];        
        $final['case'] = $obj['cpu-case'];
        $final['fuente_poder'] = $obj['cpu-fuente_poder'];               
        $final['procesador'] = $obj['cpu-procesador'];
        return $final ;
    }

    public function crear_laptop(Request $request)
    {
        DB::beginTransaction();
        try {
            $computador = new Equipo();
            $computador->codigo = $request->get('general_fields')['codigo'];
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'laptop';
            $computador->id_marca = $request->get('general_fields')['marca'];
            $computador->modelo = $request->get('general_fields')['modelo'];
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'O';
            $computador->descripcion = $request->get('general_fields')['descripcion'];
            $computador->numero_serie = $request->get('general_fields')['nserie'];
            $computador->ip = $request->get('general_fields')['ip'];
            $computador->asignado=$request->get('general_fields')['asignar'];
            $computador->save();
            if($request->get("general_fields")["ip"]!=null && $request->get("general_fields")["ip"]!=""){
                Ip::Where("id_ip","=",$request->get("general_fields")["ip"])->update(['estado' => "EU"]);
            }
            $num_slots = new DetalleComponente();
            $num_slots->campo = 'numero_slots';
            $num_slots->dato = $request->get('ram_fields')['num_slots'];
            $num_slots->id_equipo = $computador->id_equipo;
            $num_slots->save();

            $ram_soport = new DetalleComponente();
            $ram_soport->campo = 'ram_soportada';
            $ram_soport->dato = $request->get('ram_fields')['ram_soportada'];
            $ram_soport->id_equipo = $computador->id_equipo;
            $ram_soport->save();//no conexiones disco en front

            $detEq = new DetalleEquipo();
            $detEq->nombre_pc=$request->get('general_fields')['nombre_pc'];
            $detEq->usuario_pc=$request->get('general_fields')['usuario_pc'];
            $detEq->so=$request->get('so_fields')['so'];
            $detEq->office=$request->get('so_fields')['office'];
            $detEq->tipo_so=$request->get('so_fields')['tipo_so'];
            $detEq->services_pack=$request->get('so_fields')['sp1'];
            $detEq->licencia=$request->get('so_fields')['licencia'];
            $detEq->id_equipo= $computador->id_equipo;
            $detEq->save();

            $comp = new Equipo();
            $comp->id_marca = $request->get['procesador_fields']('marca_proc');
            $comp->codigo = $request->get['procesador_fields']('codigo_proc');
            $comp->modelo = $request->get['procesador_fields']('modelo_proc');
            $comp->numero_serie = $request->get['procesador_fields']('nserie_proc');
            $comp->descripcion = $request->get['procesador_fields']('descr_proc');
            $comp->encargado_registro = 'admin';
            $comp->fecha_registro = Date('Y-m-d H:i:s');
            $comp->estado_operativo = 'O';
            $comp->asignado=$request->get('general_fields')['asignar'];
            $comp->componente_principal = $computador->id_equipo;
            $comp->tipo_equipo='procesador';
            $comp->save();
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }
    /*Fin - listar computadoras y laptops - Web Version*/

    public function crear_otro_equipo(Request $request)
    {
        $equipo = new Equipo();
        $dt = new \DateTime();
        $dt->format('Y-m-d');
        $equipo->modelo = $request->get('modelo');
        $equipo->fecha_registro = $dt;
        $equipo->codigo = $request->get('codigo');
        $equipo->descripcion = $request->get('descripcion');
        $equipo->id_marca = $request->get('id_marca');
        $equipo->asignado = $request->get('asignado');
        $equipo->numero_serie = $request->get('numero_serie');
        $equipo->estado_operativo = $request->get('estado_operativo');
        $equipo->componente_principal = $request->get('componente_principal');
        $equipo->encargado_registro = $request->get('encargado_registro');
        $equipo->ip = $request->get('ip');
        $tipo = $request->get('tipo_equipo');
        if (strcasecmp($tipo, "otro") == 0) {
            $equipo->tipo_equipo = $request->get('tipo');
        } else {
            $equipo->tipo_equipo = $tipo;
        }

        /*Si el usuario elige una ip para la impresora, el
        estado de la ip debe cambiar a En uso */
        $id = $request->get('ip');
        if ($id !== null) {
            $ip = Ip::find($id);
            $ip->estado = "EU";
            $ip->save();
        }
        $equipo->save();
    }

    public function mostrar_tipo_equipo()
    {
        return Equipo::Select('tipo_equipo')
            ->whereNotIn('tipo_equipo', ["Impresora","desktop","Router","Laptop","impresora","Desktop","router","Laptop"])
            ->distinct()
            ->orderBy('tipo_equipo', 'asc')
            ->get();
    }

    public function mostrar_equipos()
    {
            return Equipo::SelectRaw('equipos.*, marcas.nombre as marca, empleados.nombre as empleado, 
            empleados.apellido,
             equipos.encargado_registro as encargado, p.codigo as principal,
             ips.direccion_ip')
            ->join('marcas', 'marcas.id_marca', '=', 'equipos.id_marca')
            ->leftjoin('ips','id_ip','=','equipos.ip')
            ->leftjoin('equipos as p','p.id_equipo','=','equipos.componente_principal') 
            ->leftjoin('empleados','equipos.asignado','=','cedula')
            ->whereNotIn('equipos.tipo_equipo', ["Impresora","desktop","Router","Laptop","impresora","Desktop","router","Laptop"])
            ->orderBy('equipos.tipo_equipo', 'asc')
            ->get();             
    }

    /* Servicio para editar otros equipos */
    public function editar_equipo(Request $request)
    {
        $equipo = Equipo::find($request->get('key')); 
        $ip_anterior = $equipo->ip; //id de la dir ip;

        DB::beginTransaction();
        try  {
            $equipo->modelo = $request->get('modelo');
            
            $equipo->descripcion = $request->get('descripcion');
            $equipo->numero_serie = $request->get('numero_serie');
            $equipo->estado_operativo = $request->get('estado_operativo');
            $equipo->componente_principal = $request->get('componente_principal'); 
            $equipo->codigo = $request->get('codigo');
            
            /*Comprobación necesaria de acuerdo a lo establecido en el formulario del 
            frontend */
            $tipo = $request->get('tipo_equipo');
            if (strcasecmp($tipo, "otro") == 0) {
                $equipo->tipo_equipo = $request->get('tipo');
            } else {
                $equipo->tipo_equipo = $tipo;
            }

            $marca = $request->get('id_marca');
            if (!is_numeric($marca)) {
                $id_marca = Marca::select('id_marca')
                    ->where('nombre', '=', $marca)
                    ->get();
                $marca = $id_marca[0]->id_marca;
            }
            $equipo->id_marca = $marca; 


             $componente= $request->get('componente_principal');
             if ($componente !== null) {
                if(!is_numeric($componente)){
                    $id_componente=Equipo::select('id_equipo')
                    ->where('codigo','=',$componente)
                    ->get();
                    $componente= $id_componente[0]->id_equipo;
                }
            }else {
                    $componente = null;
                }
            $equipo->componente_principal = $componente;


            $asignado = $request->get('asignado');
            if ($asignado !== null) {
                if (!is_numeric($asignado)) {
                    $cedula = Empleado::select('cedula')
                    ->whereRaw('CONCAT(empleados.nombre," ",empleados.apellido) like ?', ["%{$asignado}%"])
                    ->get();
                    $asignado = $cedula[0]->cedula;
                }
            } else {
                $asignado = null;
            }
            $equipo->asignado = $asignado;

        /*Debido a que hay ocasiones en que el back recibe un string como direccion ip, 
        se debe hacer una consulta para obtener el id */
        $ip_actual=$request->get('ip');
        if(!is_numeric($ip_actual)){
            if($ip_actual!==null){
                $ip=Ip::select('id_ip')
                ->where('direccion_ip','=',$ip_actual)
                ->get();
                $ip_actual = $ip[0]->id_ip;
            }else{
                $ip_actual=null;
            }
        }
        $equipo->ip = $ip_actual;     
        
         /*Si el usuario elige una nueva ip para la impresora,
         *el estado de esta debe cambiar a En uso y la anterior debe
         quedar libre. */
            if($ip_anterior!==$ip_actual){
                if($ip_actual!==null){
                    $ips= Ip::find($ip_actual);
                    $ips->estado= "EU";
                    $ips->save();
                }
            
                if($ip_anterior!==null){
                    $anterior= Ip::find($ip_anterior);
                    $anterior->estado= "L";
                    $anterior->save();
                }
            }
    
            $equipo->save();

            DB::commit();
            return response()->json(['log' => 'Registro actualizado satisfactoriamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        } 
    }

    /*Obtener los datos de un equipo a partir de su ID */
    public function equipo_id($id_equipo){
        return Equipo::SelectRaw('equipos.*, marcas.nombre as marca, 
        empleados.nombre as empleado, empleados.apellido as apellido, p.codigo as componente_principal,
         bspi_punto, departamentos.nombre as departamento, ips.direccion_ip')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('equipos as p','p.id_equipo','=','equipos.componente_principal')
        ->leftjoin('ips','id_ip','=','equipos.ip')
        ->leftjoin('empleados','equipos.asignado','=','cedula')
        ->leftjoin('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->leftjoin('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('equipos.id_equipo',$id_equipo)
        ->get();

    }

    function eliminar_equipo($id_equipo){
        $equipo = Equipo::find($id_equipo);
        $equipo->estado_operativo = 'B';
        $equipo->save();
    } 

    /*Esto fue creado en base al formato de excel llamado "Inventario Final Ok" */
    function reporte_general(){
        return Equipo::SelectRaw('equipos.*, marcas.nombre as marca, 
        empleados.nombre as empleado, empleados.apellido as apellido, 
         bspi_punto, departamentos.nombre as departamento, ips.direccion_ip')
         ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('ips','id_ip','=','equipos.ip')
        ->leftjoin('empleados','equipos.asignado','=','cedula')
        ->leftjoin('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->leftjoin('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->whereNotNull('asignado')
        ->orderBy('departamento')
        ->get()
        /* ->groupBy('departamento') */;  
    }

     /*Esto fue creado en base al formato de excel llamado "Inventario equipos Sistemas (baja)" */
     function reporte_bajas(){
        return Equipo::SelectRaw('id_equipo, codigo, tipo_equipo, modelo,numero_serie,estado_operativo,
        descripcion, marcas.nombre as marca')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->orderBy('tipo_equipo')
        ->where('estado_operativo','B')
        ->get()
        /* ->groupBy('tipo_equipo') */;
    }

    function resumen_bajas(){
        return Equipo::select(DB::raw('count(*) as cantidad, tipo_equipo'))
        ->where('estado_operativo', 'B')
        ->groupBy('tipo_equipo')
        ->get();
    }
}
