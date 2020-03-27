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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function crear_comp_laptop(Request $request){
        DB::beginTransaction();
        try{
            $computador = new Equipo();
            $computador->codigo = $request->get('pc-codigo');
            $computador->fecha_registro = Date('Y-m-d H:i:s');
            $computador->tipo_equipo = 'laptop';
            $computador->id_marca = $request->get('pc-marca');
            $computador->modelo = $request->get('pc-modelo');
            $computador->encargado_registro = 'admin';
            $computador->estado_operativo = 'OPERATIVO';
            $computador->descripcion = $request->get('pc-descripcion');
            $computador->numero_serie = $request->get('pc-num_serie');
            $computador->save();

            $num_slots = new DetalleComponente();
            $num_slots->campo = 'numero_slots';
            $num_slots->dato = $request->get('pc-num_slots');
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


            foreach($request->except(['pc-codigo','pc-descripcion',"pc-num_serie",'pc-frecuencia','pc-nucleos','pc-marca','pc-modelo','pc-ram_soportada','pc-num_slots']) as $clave => $valor){
                $comp = new Equipo();
                $comp->id_marca = $valor['marca'];
                $comp->codigo= $valor['codigo'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['num_serie'];
                $comp->descripcion = Arr::has($valor, 'descripcion')?$valor['descripcion']:'';
                $comp->encargado_registro = 'admin';
                $comp->fecha_registro = Date('Y-m-d H:i:s');
                $comp->estado_operativo = 'OPERATIVO';
                $comp->componente_principal = $computador->id_equipo;
                $comp->tipo_equipo = explode('-',$clave)[1] ;
                $comp->save();

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
                $comp->id_marca = $valor['marca'];
                $comp->modelo = $valor['modelo'];
                $comp->numero_serie = $valor['num_serie'];
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
                $comp->tipo_equipo = explode('-',$clave)[1] ;
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
                    $num_slots->dato = $valor['num_slots'];
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

    function validatorDesktop(Request $request){
        if(count($request->all())<11){
            return  response()->json(['log' => [ 'Faltan Datos' ],"obj"=>$request->all(),"count"=>count($request->all())],400);
        }
        foreach($request->all() as $clave => $valor) {
            if(!Str::contains($clave, 'case') && !Str::contains($clave, 'pc-codigo')&& !Str::contains($clave, 'pc-descripcion')){
                $validator1 = Validator::make($request->get($clave), [
                    'marca' => 'required|max:255',
                    'modelo' => 'required|max:255',
                    'num_serie' => 'required'
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

    function validatorDesktopS(Request $request){
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
                    'num_slots' => 'required|max:255'

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function getEquipos(){
        return Equipo::select("*")->wherein("tipo_equipo",["desktop","laptop","impresora","router"])->get();
    }


    public function getDesktop(Request $request){
        $result = Equipo::select("*")->where("tipo_equipo","=",$request->get("tipo"));
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
        if($request->get("fecha")!=null && $request->get("fecha")!=""){
            $result = $result->where('fecha_registro','=',$request->get("fecha"));
        }
        return response()->json($result->get());
    }

    // esta madre de aca tampoco vale xd
    // public function getidcpu($idequipo){
    //     return Equipo::select("id_equipo")->where("componente_principal","=",$idequipo)->where("tipo_equipo","=","cpu")->first();
    // }

    public function getEquipoByID( $idequipo){

        //esta madre no vale xd
        // $idcpu = self::getidcpu($idequipo);
        // $result = Equipo::select("*")->whereIn("componente_principal",[$idcpu,$idequipo]);
        // //->orWhere("id_equipo","=",$idequipo);
        // return response()->json(["log"=>$result->get(),"id"=>$idcpu]);
        //$res = DB::raw("select * from equipos where id_equipo = ".$idequipo." or componente_principal = ".$idequipo." or componente_principal = ".$idcpu);
        // $res = 
        // return response()->json($res);
        $res = DB::select(DB::raw("select * from equipos where id_equipo = ".$idequipo." or (componente_principal = ".$idequipo." or componente_principal = (select id_equipo from equipos where componente_principal = ".$idequipo." and tipo_equipo = 'cpu') );        "));
        return response()->json($res);
    }

    public function getDetalleComp(Request $request){
        $value = DetalleComponente::select("*")->whereIn("id_equipo",$request)->get();    
        return response()->json( $value);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Equipo $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipo $equipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipo $equipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipo $equipo)
    {
        //
    }


    public function mostrar_codigos()
    {
        return Equipo::select('id','codigo as dato')
        ->get();
    }
}
