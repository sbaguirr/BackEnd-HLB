<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Impresora;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Ip;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use DateTime;


class ImpresoraController extends Controller
{
    //

    public function crear_impresora(Request $request){
        $impresora = new Impresora();
        $equipo = new Equipo();
        $dt = new \DateTime();
        $dt->format('Y-m-d');

       /*  $value_marca=Marca::select('id_marca')
        ->where('nombre','=',$request->get('marca'))
        ->get();

        $equipo ->id_marca=$value_marca[0]->id_marca; */

        //$v="id_marca";
        //$equipo ->id_marca=$value_marca->$v;

    try{
        $equipo ->modelo=$request->get('modelo');

        $equipo ->fecha_registro=$dt;
        $equipo ->codigo=$request->get('codigo');
        $equipo ->tipo_equipo="Impresora";
        $equipo ->descripcion=$request->get('descripcion');
        $equipo ->id_marca=$request->get('id_marca');
        $equipo ->asignado=$request->get('asignado');
        $equipo ->numero_serie=$request->get('numero_serie');
        $equipo ->encargado_registro=$request->get('encargado_registro');
        $equipo ->estado_operativo=$request->get('estado_operativo');
        $equipo ->componente_principal=$request->get('componente_principal');
        $equipo->ip = $request->get('ip');
        $equipo->save();

        $id=$equipo->id_equipo;


        if($request->get('cinta')!==null ){
            $impresora ->cinta=$request->get('cinta');
        }
        if($request->get('toner')!==null){
            $impresora ->toner=$request->get('toner');
        }
        if($request->get('rodillo')!==null){
            $impresora ->rodillo=$request->get('rodillo');
        }
        if($request->get('rollo')!==null){
            $impresora ->rollo=$request->get('rollo');
        }

        $impresora ->tipo=$request->get('tipo');
        $impresora ->tinta=$request->get('tinta');
        $impresora ->cartucho=$request->get('cartucho');
        //$impresora ->id_equipo=(int)$request->get('id_equipo');
        $impresora ->id_equipo=$id;

        $impresora->save();

         /*Si el usuario elige una ip para la impresora, el
        estado de la ip debe cambiar a En uso */
        $ipp= $request->get('ip');
        if($ipp!==null){
            $ip= Ip::find($ipp);
            $ip->estado= "EU";
            $ip->save();
        }
        return response()->json($impresora);
    }catch(QueryException $e){
        $error_code = $e->errorInfo[1];
        if($error_code == 1062){
            return response()->json(['log'=>'El código del equipo que ha ingresado ya existe'],500);
        }
        return response()->json(['log'=>$e],500);
    }
    }

    public function mostrar_impresoras(){
        $impresoras = Impresora::all();
        return response()->json($impresoras);
    }

    public function buscar_por_fecha($fecha_asignacion)
    {
        return Correo::select('empleados.nombre','empleados.apellido','departamentos.nombre as departamento','bspi_punto','correo')
        ->join('empleados','empleados.cedula','=','correos.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->whereDate('correos.created_at',$fecha_asignacion)
        ->get();
    }

    public function mostrar_impresoras_all(){
        return Impresora::select('id_impresora','tipo','tinta','cinta','rodillo','rollo','toner','cartucho','equipos.id_equipo','estado_operativo','codigo','marcas.nombre as marca','modelo','descripcion','numero_serie','encargado_registro','equipos.created_at')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->orderBy('equipos.created_at', 'desc')

        ->get();

    }

    public function marcas_impresoras(){
        $marcas=Marca::select('nombre as marca')
        ->get();
        return response()->json($marcas);
            }

    public function impresoras_codigo($codigo){

        return Impresora::select('id_impresora','tipo','tinta','cinta','rodillo','rollo','toner','cartucho','equipos.id_equipo','estado_operativo','codigo','marcas.nombre as marca','modelo','descripcion','numero_serie','encargado_registro','equipos.created_at')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->where('equipos.codigo','like',"%".$codigo."%")
        ->orderBy('equipos.created_at', 'desc')

        ->get();

    }

    public function filtrar_impresoras($marca,$fecha_asignacion=null){
        $query = Impresora::select('id_impresora','tipo','tinta','cinta','rodillo','rollo','toner','cartucho','equipos.id_equipo','estado_operativo','codigo','marcas.nombre as marca','modelo','descripcion','numero_serie','encargado_registro','equipos.created_at')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca');



        if($marca != "Todos" && !empty($fecha_asignacion)){
            $value_marca=Marca::select('id_marca')
            ->where('nombre','=',$marca)
            ->get();
            //$value_marca[0]->id_marca;

            $query= $query->where([['equipos.created_at', 'like', "${fecha_asignacion}%"],
                ['equipos.id_marca', '=', $value_marca[0]->id_marca]]);
        }
        if ($marca != "Todos" && empty($fecha_asignacion)){
            $value_marca=Marca::select('id_marca')
            ->where('nombre','=',$marca)
            ->get();
            $query= $query->where('equipos.id_marca',$value_marca[0]->id_marca);
        }
        if ($marca == "Todos" && !empty($fecha_asignacion)){
            $query= $query->whereDate('equipos.created_at',$fecha_asignacion);
        }
        $query= $query->orderBy('equipos.created_at', 'asc')->get();
        return $query;
    }


    public function impresoras_equipo(){
        return Impresora::selectRaw('impresoras.*, equipos.*, marcas.nombre as marca, empleados.nombre as empleado, 
        empleados.apellido, equipos.encargado_registro as encargado, p.codigo as componente_principal,
        ips.direccion_ip' )
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('ips','id_ip','=','equipos.ip')
        ->leftjoin('equipos as p','p.id_equipo','=','equipos.componente_principal')
        ->leftjoin('empleados','equipos.asignado','=','cedula')
        ->leftjoin('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->leftjoin('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->get();

    }

    public function editar_impresora(Request $request){
        $impresora= Impresora::find($request->get('key')); #key es el id de la impresora.
        $equipo= Equipo::find($impresora->id_equipo);   
        $ip_anterior= $equipo->ip; #id de la dir ip;

        DB::beginTransaction();
        try{
        $equipo->estado_operativo = $request->get('estado_operativo');
        $equipo->codigo = $request->get('codigo');
        $equipo->modelo = $request->get('modelo');
        $equipo->numero_serie = $request->get('numero_serie');
        $equipo->descripcion = $request->get('descripcion');
        $equipo->encargado_registro = $request->get('encargado_registro');
       

        /*En modo edición, cuando se cargan los datos desde el formulario, el front  
        no envia el id de: marca, empleados, e ip. Pero, si el usuario hace un cambio 
        y elige otro elemento del select/combo entonces si se envía el id, por tal motivo
        es necesario esta comprobación   */

        $marca=$request->get('id_marca');
        if(!is_numeric($marca)){
            $id_marca=Marca::select('id_marca')
            ->where('nombre','=',$marca)
            ->get();
            $marca= $id_marca[0]->id_marca;
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
        if($asignado!==null){
            if(!is_numeric($asignado)){
                 $cedula=Empleado::select('cedula')
                ->whereRaw('CONCAT(empleados.nombre," ",empleados.apellido) like ?',["%{$asignado}%"])
                ->get();
                $asignado = $cedula[0]->cedula;
            }           
        }else{
            $asignado=null;
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

        if($request->get('cinta')!==null ){
            $impresora ->cinta=$request->get('cinta');
        }
        if($request->get('toner')!==null){
            $impresora ->toner=$request->get('toner');
        }
        if($request->get('rodillo')!==null){
            $impresora ->rodillo=$request->get('rodillo');
        }
        if($request->get('rollo')!==null){
            $impresora ->rollo=$request->get('rollo');
        }
        $impresora ->tipo=$request->get('tipo');
        $impresora ->tinta=$request->get('tinta');
        $impresora ->cartucho=$request->get('cartucho');
        $impresora->save(); 

        DB::commit();
        return response()->json(['log'=>'Registro actualizado satisfactoriamente'],200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log'=>$e],400);
        }catch(QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return response()->json(['log'=>'El código del equipo que ha ingresado ya existe'],500);
            }
            return response()->json(['log'=>$e],500);
        }
    }

    /*Obtener los datos de una impresora a partir de su id */
    function impresora_id($id_impresora){
        return Impresora::selectRaw('impresoras.*, equipos.*, marcas.nombre as id_marca, 
        empleados.nombre as empleado, equipos.encargado_registro as encargado, ips.direccion_ip as ip,
         empleados.apellido as apellido, p.codigo as componente_principal,
         bspi_punto, departamentos.nombre as departamento' )
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('equipos as p','p.id_equipo','=','equipos.componente_principal')
        ->leftjoin('ips','id_ip','=','equipos.ip')
        ->leftjoin('empleados','equipos.asignado','=','cedula')
        ->leftjoin('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->leftjoin('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('impresoras.id_impresora',$id_impresora)
        ->get();
    }
}