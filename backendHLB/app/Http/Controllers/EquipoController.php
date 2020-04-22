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
use Illuminate\Database\QueryException;
use DateTime;

class EquipoController extends Controller
{
    const REQ = 'El campo :attribute no puede estar vacío';
    const MAX = 'El campo :attribute supera la longitud maxima permitida';

    //creacion
    public function crear_comp_laptop(Request $request)
    {
        DB::beginTransaction();
        try {
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'laptop';
            $computador->id_marca = $request->get('pc-id_marca');
            $computador->modelo = $request->get('pc-modelo');
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'O';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->numero_serie = $request->get('pc-numero_serie');
            $computador->ip = $request->get("pc-ip");
            $computador->asignado=$request->get("pc-empleado");
            $computador->save();
            if($request->get("pc-ip")!=null && $request->get("pc-ip")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip"))->update(['estado' => "EU"]);
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
            $disc_conect->id_equipo = $comp->id_equipo;
            $disc_conect->save();

            $detEq = new DetalleEquipo();
            $detEq->nombre_pc=$request->get('pc-nombre');
            $detEq->usuario_pc=$request->get('pc-usuario');
            $detEq->so=$request->get('pc-so');
            $detEq->office=$request->get('pc-office');
            $detEq->tipo_so=$request->get('pc-tipo_so');
            $detEq->services_pack=$request->get('pc-service');
            $detEq->licencia=$request->get('pc-licencia');
            $detEq->id_equipo= $computador->id_equipo;
            $detEq->save();


            foreach($request->except(['pc-codigo','pc-descripcion',"pc-numero_serie",'pc-id_marca','pc-modelo','pc-ram_soportada','pc-slots_ram',"pc-ip","pc-empleado",'pc-nombre','pc-usuario','pc-so','pc-office','pc-tipo_so','pc-service','pc-licencia','pc-conexiones_disco']) as $clave => $valor){
                $comp = new Equipo();
                $comp->id_marca = $valor['id_marca'];
                $comp->codigo = $valor['codigo'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['numero_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion') ? $valor['descripcion'] : '';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'O';
                $comp->asignado=$request->get("pc-empleado");
                $comp->componente_principal = $computador->id_equipo;
                $comp->tipo_equipo = Str::contains($clave, '_') ? explode("_", explode('-', $clave)[1])[0] . "_" . explode("_", explode('-', $clave)[1])[1] : explode('-', $clave)[1];
                if(Str::contains($clave, 'ups_regulador')){
                    $comp->tipo_equipo=$valor['tipo_equipo'];
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

        // $validator = self::validatorDesktop($request);
        // if ($validator != null) {
        //     return $validator;
        // }

        DB::beginTransaction();

        try {
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'desktop';
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'O';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->ip = $request->get("pc-ip");
            $computador->asignado=$request->get("pc-empleado");
            $computador->save();
            if($request->get("pc-ip")!=null && $request->get("pc-ip")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip"))->update(['estado' => "EU"]);
            }

            $cpu = new Equipo();
            $cpu->componente_principal = $computador->id_equipo;
            $cpu->fecha_registro = Date('Y-m-d H:i:s');
            $cpu->tipo_equipo = 'cpu';
            $cpu->codigo = ($request->get('pc-codigo'))."_CPU";
            $cpu->encargado_registro = 'admin';
            $cpu->asignado=$request->get("pc-empleado");
            $cpu->estado_operativo = 'O';
            $cpu->save();

            $detEq = new DetalleEquipo();
            $detEq->nombre_pc=$request->get('pc-nombre');
            $detEq->usuario_pc=$request->get('pc-usuario');
            $detEq->id_equipo= $computador->id_equipo;
            $detEq->so=$request->get('pc-so');
            $detEq->tipo_so=$request->get('pc-tipo_so');
            $detEq->office=$request->get('pc-office');
            $detEq->services_pack=$request->get('pc-service');
            $detEq->licencia=$request->get('pc-licencia');
            $detEq->save();
          
            foreach($request->except(['pc-codigo','pc-descripcion',"pc-ip","pc-empleado",'pc-nombre','pc-usuario','pc-so','pc-office','pc-tipo_so','pc-service','pc-licencia',]) as $clave => $valor) {
                
                $comp = new Equipo();
                $comp->codigo = $valor['codigo'];
                $comp->id_marca = $valor['id_marca'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['numero_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion') ? $valor['descripcion'] : '';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'O';
                $comp->asignado=$request->get("pc-empleado");

                if (Str::contains($clave, 'pc')) {
                    $comp->componente_principal = $computador->id_equipo;
                }
                if (Str::contains($clave, 'cpu')) {
                    $comp->componente_principal = $cpu->id_equipo;
                }
                $comp->tipo_equipo = Str::contains($clave, '_') ? explode("_", explode('-', $clave)[1])[0] . "_" . explode("_", explode('-', $clave)[1])[1] : explode('-', $clave)[1];
                if(Str::contains($clave, 'ups_regulador')){
                    $comp->tipo_equipo=$valor['tipo_equipo'];
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

    // //validacion para creacion
    // public function validatorDesktop(Request $request)
    // {
    //     if (count($request->all()) < 11) {
    //         return  response()->json(['log' => ['Faltan Datos'], "obj" => $request->all(), "count" => count($request->all())], 400);
    //     }
    //     foreach ($request->all() as $clave => $valor) {
    //         if (!Str::contains($clave, 'case') && !Str::contains($clave, 'pc-codigo') && !Str::contains($clave, 'pc-descripcion')) {
    //             $validator1 = Validator::make($request->get($clave), [
    //                 'id_marca' => 'required|max:255',
    //                 'modelo' => 'required|max:255',
    //                 'numero_serie' => 'required'
    //             ], [
    //                 'required' => self::REQ,
    //                 'max:255' => self::MAX
    //             ]);
    //             if ($validator1->fails()) {
    //                 return  response()->json(['log' => [$clave, $validator1->errors()]], 400);
    //             }
    //         }
    //     }
    //     $valid_cod = Validator::make($request->all(), [
    //         'pc-codigo' => 'required|max:255',
    //     ], [
    //         'required' => self::REQ,
    //         'max:255' => self::MAX
    //     ]);
    //     if ($valid_cod->fails()) {
    //         return  response()->json(['log' => [$clave, $valid_cod->errors()]], 400);
    //     }
    //     $validatorS = self::validatorDesktopS($request);
    //     if ($validatorS != null) {
    //         return $validatorS;
    //     }

    //     return null;
    // }

    // public function validatorDesktopS(Request $request)
    // {
    //     foreach ($request->all() as $clave => $valor) {
    //         if (Str::contains($clave, 'memoria_ram') || Str::contains($clave, 'disco_duro')) {
    //             $validator2 = Validator::make($request->get($clave), [
    //                 'capacidad' => 'required|max:255',
    //                 'tipo' => 'required|max:255'
    //             ], [
    //                 'required' => self::REQ,
    //                 'max:255' => self::MAX
    //             ]);
    //             if ($validator2->fails()) {
    //                 return  response()->json(['log' => [$clave, $validator2->errors()]], 400);
    //             }
    //         }
    //         if (Str::contains($clave, 'tarjeta_madre')) {
    //             $validator3 = Validator::make($request->get($clave), [
    //                 'ram_soportada' => 'required|max:255',
    //                 'slots_ram' => 'required|max:255'

    //             ], [
    //                 'required' => self::REQ,
    //                 'max:255' => self::MAX
    //             ]);
    //             if ($validator3->fails()) {
    //                 return  response()->json(['log' => [$clave, $validator3->errors()]], 400);
    //             }
    //         }
    //     }
    //     return null;
    // }



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
        $ids = array();
        $res = DB::select(DB::raw("select * from equipos where id_equipo = " . $idequipo . " or (componente_principal = " . $idequipo . " or componente_principal = (select id_equipo from equipos where componente_principal = " . $idequipo . " and tipo_equipo = 'cpu') );"));
        foreach ($res as $obj) {
            array_push($ids, $obj->id_equipo);
        }
        $comp = DetalleComponente::select("*")->whereIn("id_equipo",$ids);
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $response = self::generateDataDesktop(json_decode(json_encode($res), true),$comp->get()->toArray(),$detEq->toArray()[0]);
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
        'pc-ip'=>$laptop["ip"],
        'pc-empleado'=>$laptop["asignado"],
        'pc-slots_ram'=>$num_slots["dato"],
        "pc-conexiones_disco"=>$conect_disc["dato"],
        "id-conexiones_disco"=>$conect_disc["id"],
        'id-slots_ram'=>$num_slots["id"],
        "pc-descripcion"=>$laptop["descripcion"],
        'id-ram_soportada'=> $ram_soport["id"],
        "pc-nombre"=>$detEq["nombre_pc"],
        "pc-usuario"=>$detEq["usuario_pc"],
        "pc-so"=>$detEq["so"],
        "pc-tipo_so"=>$detEq["tipo_so"],
        "pc-licencia"=>$detEq["licencia"], "pc-service"=>$detEq["services_pack"],"pc-office"=>$detEq["office"],'id-slots_ram'=>$num_slots["id"],];
        return self::filtro_dinamico_plus($final,$equipos,$detalles,["cpu-disco_duro","cpu-memoria_ram","pc-procesador","pc-ups_regulador"]);
    }


    private function generateDataDesktop($equipos,$detalles,$detEq){
        $pc= self::fil_obj($equipos,"tipo_equipo","desktop");
        $final = ["pc-codigo"=>$pc["codigo"],"pc-descripcion"=>$pc["descripcion"],
        'pc-ip'=>$pc["ip"],'pc-empleado'=>$pc["asignado"],"pc-nombre"=>$detEq["nombre_pc"],"pc-usuario"=>$detEq["usuario_pc"],"pc-so"=>$detEq["so"],"pc-tipo_so"=>$detEq["tipo_so"],"pc-licencia"=>$detEq["licencia"],"pc-service"=>$detEq["services_pack"],"pc-office"=>$detEq["office"],];
        return self::filtro_dinamico_plus($final,$equipos,$detalles,["cpu-disco_duro","cpu-memoria_ram",'pc-monitor', 'pc-teclado', 'pc-parlantes', 'pc-mouse','cpu-tarjeta_red', 'cpu-case', 'cpu-fuente_poder','cpu-tarjeta_madre', 'cpu-procesador',"pc-ups_regulador"]);
    }


    //funciones auxiliares para getLaptopByID
    private function fil_obj($array, $key, $value)
    {
        return array_values(array_filter($array, function ($obj) use ($key, $value) {
            return $obj[$key] === $value;
        }))[0];
    }


    private function filtro_dinamico_plus($final,$equipos,$detalles,$claves){
        for($k=0; $k<count($claves); $k++){
            $arr = array_values(array_filter($equipos, function($obj) use($claves,$k){return Str::contains(explode('-',$claves[$k])[1],$obj["tipo_equipo"]);}));
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
            if($ip_old!=null){
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
            if($ip_old!=null){
                Ip::Where("id_ip","=",$ip_old)->update(['estado' => "L"]);
            }
            Equipo::Where("id_equipo","=", $idequipo)->update(["codigo"=>$request->get("pc-codigo"),"descripcion"=>$request->get("pc-descripcion"),"ip"=>$request->get("pc-ip"),"asignado"=>$request->get("pc-empleado")]);
            if($request->get("pc-ip")!=null && $request->get("pc-ip")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip"))->update(['estado' => "EU"]);
            }
            DetalleEquipo::Where("id_equipo","=",$idequipo)->update(["usuario_pc"=>$request->get("pc-usuario"),"nombre_pc"=>$request->get("pc-nombre"), "so"=>$request->get('pc-so'),"tipo_so"=>$request->get('pc-tipo_so'),"service_pack"=>$request->get('pc-service'),"licencia"=>$request->get('pc-licencia')]);
            self::editDeskAux($request,['pc-teclado'=>[], 'pc-parlantes'=>[], 'pc-mouse'=>[],'cpu-tarjeta_red'=>[], 'cpu-case'=>[], 'cpu-fuente_poder'=>[],'cpu-tarjeta_madre'=>['ram_soportada', 'slots_ram', 'disc_conect'], 'cpu-procesador'=>["frecuencia","nucleos"]]);
            self::editEquipoAux($request,"memoria_ram","cpu");
            self::editEquipoAux($request,"disco_duro","cpu");
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
            Equipo::Where("id_equipo", "=", $request->get($key)["id_equipo"])->update(["codigo" => $request->get($key)["codigo"], "id_marca" => $request->get($key)["id_marca"], "modelo" => $request->get($key)["modelo"], "numero_serie" => $request->get($key)["numero_serie"], "descripcion" => $request->get($key)["descripcion"]]);
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
            if($ip_old!=null){
                Ip::Where("id_ip","=",$ip_old)->update(['estado' => "L"]);
            }
            Equipo::Where("id_equipo","=", $idequipo)->update(["codigo"=>$request->get("pc-codigo"),"id_marca"=>$request->get("pc-id_marca"), "modelo"=>$request->get("pc-modelo"),"numero_serie"=>$request->get("pc-numero_serie"),"descripcion"=>$request->get("pc-descripcion")]);
            if($request->get("pc-ip")!=null && $request->get("pc-ip")!=""){
                Ip::Where("id_ip","=",$request->get("pc-ip"))->update(['estado' => "EU"]);
            }
            DetalleEquipo::Where("id_equipo","=",$idequipo)->update(["usuario_pc"=>$request->get("pc-usuario"),"nombre_pc"=>$request->get("pc-nombre"), "so"=>$request->get('pc-so'),
            "tipo_so"=>$request->get('pc-tipo_so'),
           "service_pack"=>$request->get('pc-service'),
           "licencia"=>$request->get('pc-licencia')]);
            self::editDetAux( $request,["ram_soportada","slots_ram"]);
            self::editDeskAux($request,['pc-procesador'=>["frecuencia","nucleos"]]);
            self::editEquipoAux($request,"memoria_ram","cpu",$idequipo);
            self::editEquipoAux($request,"disco_duro","pc",$idequipo);
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
                Equipo::Where("id_equipo","=", $request->get($nomb))->update(["codigo"=>$request->get($nomb)['codigo'],"id_marca"=>$request->get($nomb)['id_marca'], "modelo"=>$request->get($nomb)['modelo'],"numero_serie"=>$request->get($nomb)['numero_serie'],"descripcion"=>$request->get($nomb)['descripcion']]);
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
    public function darDeBajaEquipoID($idequipo,$tipo)//metodo corregido del original, no se ha tocado el anterior para evitar conflictos.
    {
        try {
            $ip_old=Equipo::select("ip")->where("id_equipo","=", $idequipo)->get();
            if($ip_old!==null){
                Ip::Where("id_ip","=",$ip_old[0]->ip)->update(['estado' => "L"]);
                Equipo::Where("id_equipo","=", $idequipo)->update(["ip" => null]);
            }
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
        $eq = Equipo::select('id_equipo')->where('tipo_equipo','=','laptop')->get();
        for ( $i=0; $i<count($eq); $i++ ){
            $varr = self::obtenerInfoLaptop($eq[$i]["id_equipo"]);
            array_push($final, $varr);
        }
        return ($final);
    }

    public function obtenerInfoLaptop($idequipo){
        $laptops= Equipo::Where("id_equipo","=",$idequipo)->orWhere("componente_principal","=",$idequipo);
        $marca = Marca::Where("id_marca","=",$laptops->get(['id_marca']));
        $ip = Ip::WhereIn("id_ip",$laptops->get(['ip']));
        $empleado = Empleado::WhereIn("cedula",$laptops->get(['asignado']));
        $dpto = Departamento::WhereIn("id_departamento",$empleado->get(['id_departamento']));
        $punto = Organizacion::WhereIn("id_organizacion",$dpto->get(['id_organizacion']));
        $compenentes = DetalleComponente::WhereIn("id_equipo",$laptops->get(['id_equipo']));  
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $var = self::generarDetalleLaptop($laptops->get()->toArray(),$compenentes->get()->toArray(),$detEq,
        $empleado->get()->toArray(), $dpto->get()->toArray(), $punto->get()->toArray(),$marca->get()->toArray(),$ip->get()->toArray());
        return response()->json($var);
    }

    private function generarDetalleLaptop($equipos, $detalles, $detEq, $empleado, $dpto, $punto, $marca, $ip)
    {
        $rams = array();
        $discos = array();
        $procesador = array();
        for ( $i=0; $i<count($equipos); $i++ ){
            $marca = Marca::Where("id_marca","=",$equipos[$i]["id_marca"])->get();
            if ($equipos[$i]["tipo_equipo"] === "memoria_ram" || $equipos[$i]["tipo_equipo"] === "disco_duro"){
                if ($equipos[$i]["tipo_equipo"] === "memoria_ram"){
                    $equipos[$i]['marca'] = $marca['0']["nombre"];
                    for ( $k=0; $k<count($detalles); $k++ ){
                        if($detalles[$k]['id_equipo']===$equipos[$i]["id_equipo"]){
                            if($detalles[$k]['campo']==='capacidad'){
                                $equipos[$i]['capacidad'] = $detalles[$k]["dato"];   
                            }
                            if($detalles[$k]['campo']==='tipo'){
                                $equipos[$i]['tipo'] = $detalles[$k]["dato"];   
                            }
                        }
                    }
                    array_push($rams, $equipos[$i]);
                }elseif ($equipos[$i]["tipo_equipo"] === "disco_duro"){
                    $equipos[$i]['marca'] = $marca['0']["nombre"];
                    for ( $k=0; $k<count($detalles); $k++ ){
                        if($detalles[$k]['id_equipo']===$equipos[$i]["id_equipo"]){
                            
                            if($detalles[$k]['campo']==='capacidad'){
                                $equipos[$i]['capacidad'] = $detalles[$k]["dato"];   
                            }
                            if($detalles[$k]['campo']==='tipo'){
                                $equipos[$i]['tipo'] = $detalles[$k]["dato"];   
                            }                        
                        }
                    }
                    array_push($discos, $equipos[$i]); 
                }
            }elseif ($equipos[$i]["tipo_equipo"] === "procesador"){
                $equipos[$i]['marca'] = $marca['0']["nombre"];
                $frecuencia = self::fil_obj($detalles,"campo","frecuencia");
                $nucleos = self::fil_obj($detalles,"campo","nucleos");
                $equipos[$i]['frecuencia'] = $frecuencia["dato"];                
                $equipos[$i]['nucleos'] = $nucleos["dato"];
                array_push($procesador, $equipos[$i]);
            }
        }
        $laptop =  self::fil_obj($equipos,"tipo_equipo","laptop");
        $marca = Marca::Where("id_marca","=",$laptop["id_marca"])->get();
        $laptop["marca"] = $marca['0']['nombre'];
        $ram_soport = self::fil_obj($detalles,"campo","ram_soportada");
        $num_slots = self::fil_obj($detalles,"campo","numero_slots");
        if($empleado !== []){
            $laptop["empleado"] = $empleado['0']["nombre"];            
            $laptop["apellido"] = $empleado['0']["apellido"];
            $laptop["departamento"] = $dpto['0']["nombre"];
            $laptop["bspi"] = $punto['0']["bspi_punto"];
        };
        if($ip !== []){
            $laptop["direccion_ip"] = $ip['0']['direccion_ip'];
        };
        $final = ["ram_soportada" => $ram_soport["dato"], "numero_slots" => $num_slots["dato"], "general" => $laptop, 
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
        $ip = Ip::WhereIn("id_ip",$laptops->get(['ip']));
        $empleado = Empleado::WhereIn("cedula",$laptops->get(['asignado']));
        $dpto = Departamento::WhereIn("id_departamento",$empleado->get(['id_departamento']));
        $punto = Organizacion::WhereIn("id_organizacion",$dpto->get(['id_organizacion']));
        $compenentes = DetalleComponente::WhereIn("id_equipo",$laptops->get(['id_equipo']));  
        $detEq= DetalleEquipo::Where("id_equipo","=",$idequipo)->get();
        $var = self::generarDetalleDesktop($laptops->get()->toArray(),$compenentes->get()->toArray(),$detEq,
        $empleado->get()->toArray(), $dpto->get()->toArray(), $punto->get()->toArray(),$marca->get()->toArray(),$ip->get()->toArray());
        return response()->json($var);
    }

    private function generarDetalleDesktop($equipos, $detalles, $detEq, $empleado, $dpto, $punto, $marcas, $ip)
    {
        $rams = array();
        $discos = array();
        $fuente_alimentacion = array();
        for ( $i=0; $i<count($equipos); $i++ ){
            if($equipos[$i]["id_marca"] !== null){
                $marca = Marca::Where("id_marca","=",$equipos[$i]["id_marca"])->get();
                $equipos[$i]['marca'] = $marca['0']["nombre"];
            }
            if ($equipos[$i]["tipo_equipo"] === "memoria_ram" || $equipos[$i]["tipo_equipo"] === "disco_duro"){
                if ($equipos[$i]["tipo_equipo"] === "memoria_ram"){
                    $equipos[$i]['marca'] = $marca['0']["nombre"];
                    for ( $k=0; $k<count($detalles); $k++ ){
                        if($detalles[$k]['id_equipo']===$equipos[$i]["id_equipo"]){
                            if($detalles[$k]['campo']==='capacidad'){
                                $equipos[$i]['capacidad'] = $detalles[$k]["dato"];   
                            }
                            if($detalles[$k]['campo']==='tipo'){
                                $equipos[$i]['tipo'] = $detalles[$k]["dato"];   
                            }
                        }
                    }
                    array_push($rams, $equipos[$i]);
                }elseif ($equipos[$i]["tipo_equipo"] === "disco_duro"){
                    $equipos[$i]['marca'] = $marca['0']["nombre"];
                    for ( $k=0; $k<count($detalles); $k++ ){
                        if($detalles[$k]['id_equipo']===$equipos[$i]["id_equipo"]){ 
                            if($detalles[$k]['campo']==='capacidad'){
                                $equipos[$i]['capacidad'] = $detalles[$k]["dato"];   
                            }
                            if($detalles[$k]['campo']==='tipo'){
                                $equipos[$i]['tipo'] = $detalles[$k]["dato"];   
                            }                        
                        }
                    }
                    array_push($discos, $equipos[$i]); 
                }
            }elseif ($equipos[$i]["tipo_equipo"] === "ups"){
                array_push($fuente_alimentacion, $equipos[$i]);
            }elseif ($equipos[$i]["tipo_equipo"] === "regulador"){
                array_push($fuente_alimentacion, $equipos[$i]);
            }
        }
        $laptop =  self::fil_obj($equipos,"tipo_equipo","desktop");
        if($empleado !== []){
            $laptop["empleado"] = $empleado['0']["nombre"];            
            $laptop["apellido"] = $empleado['0']["apellido"];
            $laptop["departamento"] = $dpto['0']["nombre"];
            $laptop["bspi"] = $punto['0']["bspi_punto"];
        };
        if($ip !== []){
            $laptop["direccion_ip"] = $ip['0']['direccion_ip'];
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


    public function editar_laptop(Request $request)
    {
        DB::beginTransaction();
        try {
            $computador = Equipo::find($request->key);
            $ip_anterior= $computador->ip; 
            $marca = $request->get('general_fields')['marca'];
            if(!is_numeric($marca)){
                $id_marca=Marca::select('id_marca')->where('nombre','=',$marca)->get();
                $marca= $id_marca[0]->id_marca;
            }
            $computador->id_marca = $marca;

            $computador->modelo = $request->get('general_fields')['modelo'];
            $computador->estado_operativo = $request->get('general_fields')['estado'];
            $computador->descripcion = $request->get('general_fields')['descripcion'];
            $computador->numero_serie = $request->get('general_fields')['nserie'];
            $ip = $request->get('general_fields')['ip'];
            if($ip === null){
                $ip = null;
            }elseif(!is_numeric($ip)){
                $id_ip=Ip::select('id_ip')->where('direccion_ip','=',$ip)->get();
                $ip= $id_ip[0]->id_ip;
            }
            $computador->ip = $ip;

            if($ip_anterior!==$ip){
                if($ip!==null){
                    $ips= Ip::find($ip);
                    $ips->estado= "EU";
                    $ips->save();
                }
            
                if($ip_anterior!==null){
                    $anterior= Ip::find($ip_anterior);
                    $anterior->estado= "L";
                    $anterior->save();
                }
            }
            $computador->asignado=$request->get('general_fields')['asignar'];
            $computador->save();
            
            DetalleComponente::where("id_equipo","=",$request->key)->where("campo","=","numero_slots")->update([
                "dato" => $request->get('memoria_ram')['num_slots']
            ]);
            DetalleComponente::where("id_equipo","=",$request->key)->where("campo","=","ram_soportada")->update([
                "dato" => $request->get('memoria_ram')['ram_soportada']
            ]);
            
            DetalleEquipo::where("id_equipo","=",$request->key)->update([
                "nombre_pc"=>$request->get('general_fields')['nombre_pc'],
                "usuario_pc"=>$request->get('general_fields')['usuario_pc'],
                "so"=>$request->get('so_fields')['so'],
                "office"=>$request->get('so_fields')['office'],
                "tipo_so"=>$request->get('so_fields')['tipo_so'],
                "services_pack"=>$request->get('so_fields')['sp1'],
                "licencia"=>$request->get('so_fields')['licencia']
            ]);            
            
            $marca_proc = $request->get('procesador_fields')['marca_proc'];
            if(!is_numeric($marca_proc)){
                $id_marca=Marca::select('id_marca')->where('nombre','=',$marca_proc)->get();
                $marca_proc= $id_marca[0]->id_marca;
            }
            Equipo::where("componente_principal","=",$request->key)->where("tipo_equipo", "=", "procesador")->update([
                "id_marca" => $marca_proc,
                "modelo" => $request->get('procesador_fields')['modelo_proc'],
                "numero_serie" => $request->get('procesador_fields')['nserie_proc'],
                "descripcion" => $request->get('procesador_fields')['descr_proc'],
                "estado_operativo" => $request->get('general_fields')['estado'],
                "asignado" => $request->get('general_fields')['asignar']
            ]);
            $id_procesador = Equipo::select("id_equipo")->where("componente_principal","=",$request->key)->where("tipo_equipo","=","procesador")->get();

            DetalleComponente::where("id_equipo","=",$id_procesador[0]->id_equipo)->where("campo","=","nucleos")->update([
                "dato" => $request->get('procesador_fields')['nucleos_proc']
            ]);
            DetalleComponente::where("id_equipo","=",$id_procesador[0]->id_equipo)->where("campo","=","frecuencia")->update([
                "dato" => $request->get('procesador_fields')['frec_proc']
            ]);
        
            foreach($request->except(['step', 'titulo', 'disabled', 'key','marc','general_fields', 'so_fields', 'procesador_fields']) as $clave => $valor){
                foreach($valor['datos'] as $k => $data){
                    $id_equipo = Equipo::select("id_equipo")->where("componente_principal","=",$request->key)
                    ->where("codigo","=",$data['codigo'])->where("tipo_equipo", "=", $clave)->get();

                    Equipo::where("componente_principal","=",$request->key)->where("codigo","=",$data['codigo'])
                    ->where("tipo_equipo", "=", $clave)->update([
                        "id_marca" => $data['marca'],
                        "modelo" => $data['modelo'],
                        "numero_serie" => $data['nserie'],
                        "descripcion" => $data['descr'],
                        "estado_operativo" => $request->get('general_fields')['estado'],
                        "asignado" => $request->get('general_fields')['asignar']
                    ]);

                    DetalleComponente::where("id_equipo","=",$id_equipo[0]->id_equipo)->where("campo","=","tipo")->update([
                        "dato" => $data['tipo']
                    ]);
                    DetalleComponente::where("id_equipo","=",$id_equipo[0]->id_equipo)->where("campo","=","capacidad")->update([
                        "dato" => $data['capacidad']['cant'] . " " . $data['capacidad']['un'] 
                    ]);
                }
            }
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
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
            $computador->estado_operativo = $request->get('general_fields')['estado'];
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
            $num_slots->dato = $request->get('memoria_ram')['num_slots'];
            $num_slots->id_equipo = $computador->id_equipo;
            $num_slots->save();

            $ram_soport = new DetalleComponente();
            $ram_soport->campo = 'ram_soportada';
            $ram_soport->dato = $request->get('memoria_ram')['ram_soportada'] . " GB";
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

            $proc = new Equipo();
            $proc->id_marca = $request->get('procesador_fields')['marca_proc'];
            $proc->codigo = $request->get('procesador_fields')['codigo_proc'];
            $proc->modelo = $request->get('procesador_fields')['modelo_proc'];
            $proc->numero_serie = $request->get('procesador_fields')['nserie_proc'];
            $proc->descripcion = $request->get('procesador_fields')['descr_proc'];
            $proc->encargado_registro = 'admin';
            $proc->fecha_registro = Date('Y-m-d H:i:s');
            $proc->estado_operativo = $request->get('general_fields')['estado'];
            $proc->asignado=$request->get('general_fields')['asignar'];
            $proc->componente_principal = $computador->id_equipo;
            $proc->tipo_equipo = 'procesador';
            $proc->save();

            $nucleos = new DetalleComponente();
            $nucleos->campo = 'nucleos';
            $nucleos->dato = $request->get('procesador_fields')['nucleos_proc'];
            $nucleos->id_equipo = $proc->id_equipo;
            $nucleos->save();

            $frec = new DetalleComponente();
            $frec->campo = 'frecuencia';
            $frec->dato = $request->get('procesador_fields')['frec_proc'];
            $frec->id_equipo = $proc->id_equipo;
            $frec->save();

            foreach($request->except(['step', 'titulo', 'disabled', 'key', 'marc', 'general_fields', 'so_fields', 'procesador_fields']) as $clave => $valor){
                foreach($valor['datos'] as $k => $data){
                    $comp = new Equipo();
                    $comp->id_marca = $data['marca'];
                    $comp->codigo = $data['codigo'];
                    $comp->modelo = $data['modelo'];
                    $comp->numero_serie = $data['nserie'];
                    $comp->descripcion = $data['descr'];
                    $comp->encargado_registro = 'admin';
                    $comp->fecha_registro = Date('Y-m-d H:i:s');
                    $comp->estado_operativo = $request->get('general_fields')['estado'];
                    $comp->asignado=$request->get('general_fields')['asignar'];
                    $comp->componente_principal = $computador->id_equipo;
                    $comp->tipo_equipo=$clave;
                    $comp->save();

                    $tipo = new DetalleComponente();
                    $tipo->campo = 'tipo';
                    $tipo->dato = $data['tipo'];
                    $tipo->id_equipo = $comp->id_equipo;
                    $tipo->save();
                    
                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'capacidad';
                    $capacidad->dato = $data['capacidad']['cant'] . " " . $data['capacidad']['un'] ;
                    $capacidad->id_equipo = $comp->id_equipo;
                    $capacidad->save();
                }
            }
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }

    public function editar_desktop(Request $request)
    {
        DB::beginTransaction();
        try {
            $computador = Equipo::find($request->key);
            $ip_anterior= $computador->ip; 
            $computador->estado_operativo = $request->get('general')['estado'];
            $computador->descripcion = $request->get('general')['descripcion'];
            $ip = $request->get('general')['ip'];
            if($ip === null){
                $ip = null;
            }elseif(!is_numeric($ip)){
                $id_ip=Ip::select('id_ip')->where('direccion_ip','=',$ip)->get();
                $ip= $id_ip[0]->id_ip;
            }
            $computador->ip = $ip;
            if($ip_anterior!==$ip){
                if($ip!==null){
                    $ips= Ip::find($ip);
                    $ips->estado= "EU";
                    $ips->save();
                }
                if($ip_anterior!==null){
                    $anterior= Ip::find($ip_anterior);
                    $anterior->estado= "L";
                    $anterior->save();
                }
            }
            $computador->asignado=$request->get('general')['asignar'];
            $computador->save();
            
            DetalleEquipo::where("id_equipo","=",$request->key)->update([
                "nombre_pc"=>$request->get('general')['nombre_pc'],
                "usuario_pc"=>$request->get('general')['usuario_pc'],
                "so"=>$request->get('so')['so'],
                "office"=>$request->get('so')['office'],
                "tipo_so"=>$request->get('so')['tipo_so'],
                "services_pack"=>$request->get('so')['sp1'],
                "licencia"=>$request->get('so')['licencia']
            ]);            
            
            $marca_proc = $request->get('procesador')['marca_proc'];
            if(!is_numeric($marca_proc)){
                $id_marca=Marca::select('id_marca')->where('nombre','=',$marca_proc)->get();
                $marca_proc= $id_marca[0]->id_marca;
            }
            Equipo::where("componente_principal","=",$request->key)->where("tipo_equipo", "=", "procesador")->update([
                "id_marca" => $marca_proc,
                "modelo" => $request->get('procesador')['modelo_proc'],
                "numero_serie" => $request->get('procesador')['nserie_proc'],
                "descripcion" => $request->get('procesador')['descr_proc'],
                "estado_operativo" => $request->get('general')['estado'],
                "asignado" => $request->get('general')['asignar']
            ]);
            $id_procesador = Equipo::select("id_equipo")->where("componente_principal","=",$request->key)->where("tipo_equipo","=","procesador")->get();
            DetalleComponente::where("id_equipo","=",$id_procesador[0]->id_equipo)->where("campo","=","nucleos")->update([
                "dato" => $request->get('procesador')['nucleos_proc']
            ]);
            DetalleComponente::where("id_equipo","=",$id_procesador[0]->id_equipo)->where("campo","=","frecuencia")->update([
                "dato" => $request->get('procesador')['frec_proc']
            ]);

            if($request->get('fuente_alimentacion')['tipo'] !== 'ninguno'){
                $marca = $request->get('fuente_alimentacion')['marca'];
                if(!is_numeric($marca)){
                    $id_marca=Marca::select('id_marca')->where('nombre','=',$marca)->get();
                    $marca= $id_marca[0]->id_marca;
                }
                Equipo::where("componente_principal","=",$request->key)->where("codigo", "=", $request->get('fuente_alimentacion')['codigo'])->update([
                    "id_marca" => $marca,
                    "modelo" => $request->get('fuente_alimentacion')['modelo'],
                    "numero_serie" => $request->get('fuente_alimentacion')['nserie'],
                    "descripcion" => $request->get('fuente_alimentacion')['descr'],
                    "estado_operativo" => $request->get('general')['estado'],
                    "asignado" => $request->get('general')['asignar']
                ]);
            }
        
            foreach($request->except(['step', 'titulo', 'disabled', 'key','general', 'so', 'procesador', 'fuente_alimentacion']) as $clave => $valor){
                if($valor['nombre'] !== 'disco duro' && $valor['nombre'] !== 'memoria RAM'){
                    $marca = $valor['marca'];
                    if(!is_numeric($marca)){
                        $id_marca=Marca::select('id_marca')->where('nombre','=',$marca)->get();
                        $marca = $id_marca[0]->id_marca;
                    }
                    Equipo::where("componente_principal","=",$request->key)
                    ->where("codigo","=",$valor['codigo'])->update([
                        "id_marca" => $marca,
                        "modelo" => $valor['modelo'],
                        "numero_serie" => $valor['nserie'],
                        "descripcion" => $valor['descr'],
                        "estado_operativo" => $request->get('general')['estado'],
                        "asignado" => $request->get('general')['asignar']
                    ]);
                    if($clave === 'mainboard'){
                        $id_equipo1 = Equipo::select("id_equipo")->where("componente_principal","=",$request->key)
                        ->where("codigo","=",$valor['codigo'])->get();
                        DetalleComponente::where("id_equipo","=",$id_equipo1[0]->id_equipo)->where("campo","=","numero_slots")->update([
                            "dato" => $request->get('mainboard')['num_slots']
                        ]);
                        DetalleComponente::where("id_equipo","=",$id_equipo1[0]->id_equipo)->where("campo","=","ram_soportada")->update([
                            "dato" => $request->get('mainboard')['ram_soportada']
                        ]);
                        DetalleComponente::where("id_equipo","=",$id_equipo1[0]->id_equipo)->where("campo","=","conexiones_dd")->update([
                            "dato" => $request->get('mainboard')['conexiones_dd']
                        ]);
                    }
                } else {
                    foreach($valor['datos'] as $k => $data){
                        $id_equipo = Equipo::select("id_equipo")->where("componente_principal","=",$request->key)
                        ->where("codigo","=",$data['codigo'])->where("tipo_equipo", "=", $clave)->get();
                        Equipo::where("id_equipo","=",$id_equipo[0]->id_equipo)->update([
                            "id_marca" => $data['marca'],
                            "modelo" => $data['modelo'],
                            "numero_serie" => $data['nserie'],
                            "descripcion" => $data['descr'],
                            "estado_operativo" => $request->get('general')['estado'],
                            "asignado" => $request->get('general')['asignar']
                        ]);
                        DetalleComponente::where("id_equipo","=",$id_equipo[0]->id_equipo)->where("campo","=","tipo")->update([
                            "dato" => $data['tipo']
                        ]);
                        DetalleComponente::where("id_equipo","=",$id_equipo[0]->id_equipo)->where("campo","=","capacidad")->update([
                            "dato" => $data['capacidad']['cant'] . " " . $data['capacidad']['un'] 
                        ]);
                    }
                }          
            }
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }

    public function crear_desktop(Request $request)
    {
        DB::beginTransaction();
        try {
            $computador = new Equipo();
            $computador->codigo = $request->get('general')['codigo'];
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'desktop';
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = $request->get('general')['estado'];
            $computador->descripcion = $request->get('general')['descripcion'];
            $computador->ip = $request->get('general')['ip'];
            $computador->asignado=$request->get('general')['asignar'];
            $computador->save();
            if($request->get("general")["ip"]!=null && $request->get("general")["ip"]!=""){
                Ip::Where("id_ip","=",$request->get("general")["ip"])->update(['estado' => "EU"]);
            }

            $detEq = new DetalleEquipo();
            $detEq->nombre_pc=$request->get('general')['nombre_pc'];
            $detEq->usuario_pc=$request->get('general')['usuario_pc'];
            $detEq->so=$request->get('so')['so'];
            $detEq->office=$request->get('so')['office'];
            $detEq->tipo_so=$request->get('so')['tipo_so'];
            $detEq->services_pack=$request->get('so')['sp1'];
            $detEq->licencia=$request->get('so')['licencia'];
            $detEq->id_equipo= $computador->id_equipo;
            $detEq->save();

            $proc = new Equipo();
            $proc->id_marca = $request->get('procesador')['marca_proc'];
            $proc->codigo = $request->get('procesador')['codigo_proc'];
            $proc->modelo = $request->get('procesador')['modelo_proc'];
            $proc->numero_serie = $request->get('procesador')['nserie_proc'];
            $proc->descripcion = $request->get('procesador')['descr_proc'];
            $proc->encargado_registro = 'admin';
            $proc->fecha_registro = Date('Y-m-d H:i:s');
            $proc->estado_operativo = $request->get('general')['estado'];
            $proc->asignado=$request->get('general')['asignar'];
            $proc->componente_principal = $computador->id_equipo;
            $proc->tipo_equipo = 'procesador';
            $proc->save();

            $nucleos = new DetalleComponente();
            $nucleos->campo = 'nucleos';
            $nucleos->dato = $request->get('procesador')['nucleos_proc'];
            $nucleos->id_equipo = $proc->id_equipo;
            $nucleos->save();

            $frec = new DetalleComponente();
            $frec->campo = 'frecuencia';
            $frec->dato = $request->get('procesador')['frec_proc'];
            $frec->id_equipo = $proc->id_equipo;
            $frec->save();

            if($request->get('fuente_alimentacion')['tipo'] !== 'ninguno'){
                $falim = new Equipo();
                $falim->id_marca = $request->get('fuente_alimentacion')['marca'];
                $falim->codigo = $request->get('fuente_alimentacion')['codigo'];
                $falim->modelo = $request->get('fuente_alimentacion')['modelo'];
                $falim->numero_serie = $request->get('fuente_alimentacion')['nserie'];
                $falim->descripcion = $request->get('fuente_alimentacion')['descr'];
                $falim->encargado_registro = 'admin';
                $falim->fecha_registro = Date('Y-m-d H:i:s');
                $falim->estado_operativo = $request->get('general')['estado'];
                $falim->asignado=$request->get('general')['asignar'];
                $falim->componente_principal = $computador->id_equipo;
                $falim->tipo_equipo=$request->get('fuente_alimentacion')['tipo'];               
                $falim->save();
            }

            foreach($request->except(['step', 'titulo', 'key', 'general', 'so', 'procesador', 'fuente_alimentacion']) as $clave => $valor){
                if($valor['nombre'] !== 'disco duro' && $valor['nombre'] !== 'memoria RAM'){
                    $component = new Equipo();
                    $component->id_marca = $valor['marca'];
                    $component->codigo = $valor['codigo'];
                    $component->modelo = $valor['modelo'];
                    $component->numero_serie = $valor['nserie'];
                    $component->descripcion = $valor['descr'];
                    $component->encargado_registro = 'admin';
                    $component->fecha_registro = Date('Y-m-d H:i:s');
                    $component->estado_operativo = $request->get('general')['estado'];
                    $component->asignado=$request->get('general')['asignar'];
                    $component->componente_principal = $computador->id_equipo;
                    if($clave === 'fuente_alimentacion'){
                        $component->tipo_equipo=$valor['tipo'];
                    }else{
                        $component->tipo_equipo=$valor['nombre'];
                    }
                    
                    $component->save();
                    if($clave === 'mainboard'){
                        $num_slots = new DetalleComponente();
                        $num_slots->campo = 'numero_slots';
                        $num_slots->dato = $request->get('mainboard')['num_slots'];
                        $num_slots->id_equipo = $component->id_equipo;
                        $num_slots->save();

                        $ram_soport = new DetalleComponente();
                        $ram_soport->campo = 'ram_soportada';
                        $ram_soport->dato = $request->get('mainboard')['ram_soportada'] . " GB";
                        $ram_soport->id_equipo = $component->id_equipo;
                        $ram_soport->save();

                        $conexiones_dd = new DetalleComponente();
                        $conexiones_dd->campo = 'conexiones_dd';
                        $conexiones_dd->dato = $request->get('mainboard')['conexiones_dd'];
                        $conexiones_dd->id_equipo = $component->id_equipo;
                        $conexiones_dd->save();
                    }
                } else {
                    foreach($valor['datos'] as $k => $data){
                        $comp = new Equipo();
                        $comp->id_marca = $data['marca'];
                        $comp->codigo = $data['codigo'];
                        $comp->modelo = $data['modelo'];
                        $comp->numero_serie = $data['nserie'];
                        $comp->descripcion = $data['descr'];
                        $comp->encargado_registro = 'admin';
                        $comp->fecha_registro = Date('Y-m-d H:i:s');
                        $comp->estado_operativo = $request->get('general')['estado'];
                        $comp->asignado=$request->get('general')['asignar'];
                        $comp->componente_principal = $computador->id_equipo;
                        $comp->tipo_equipo=$clave;
                        $comp->save();

                        $tipo = new DetalleComponente();
                        $tipo->campo = 'tipo';
                        $tipo->dato = $data['tipo'];
                        $tipo->id_equipo = $comp->id_equipo;
                        $tipo->save();
                        
                        $capacidad = new DetalleComponente();
                        $capacidad->campo = 'capacidad';
                        $capacidad->dato = $data['capacidad']['cant'] . " " . $data['capacidad']['un'] ;
                        $capacidad->id_equipo = $comp->id_equipo;
                        $capacidad->save();
                    }
                }
            }
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
        try{
        $equipo = new Equipo();
        $dt = new \DateTime();
        $dt->format('Y-m-d');
        $equipo->codigo = $request->get('codigo');
        $equipo->modelo = $request->get('modelo');
        $equipo->fecha_registro = $dt;
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

        $id = $request->get('ip');
        if ($id !== null) {
            $ip = Ip::find($id);
            $ip->estado = "EU";
            $ip->save();
        }
        $equipo->save();
    }catch(QueryException $e){
        $error_code = $e->errorInfo[1];
        if($error_code == 1062){
            return response()->json(['log'=>'El código del equipo que ha ingresado ya existe'],500);
        }
        return response()->json(['log'=>$e],500);
    }
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
            $equipo->codigo = $request->get('codigo');
            $equipo->modelo = $request->get('modelo');
            $equipo->descripcion = $request->get('descripcion');
            $equipo->numero_serie = $request->get('numero_serie');
            $equipo->estado_operativo = $request->get('estado_operativo');
            $equipo->componente_principal = $request->get('componente_principal'); 
            
            
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
        } catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El código del equipo que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);
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
    #debo considerar algo más? -s
    function eliminar_equipo($id_equipo){
        $equipo = Equipo::find($id_equipo);
        $equipo->estado_operativo = 'B';
        $equipo->componente_principal = null;
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
    
    /* Servicio que resume la cantidad de equipos que estan dados de baja.
     * Esto es utilizado para cargar datos en el archivo que se exporta desde la página web */
    function resumen_bajas(){
        return Equipo::select(DB::raw('count(*) as cantidad, tipo_equipo'))
        ->where('estado_operativo', 'B')
        ->groupBy('tipo_equipo')
        ->get();
    }
}
