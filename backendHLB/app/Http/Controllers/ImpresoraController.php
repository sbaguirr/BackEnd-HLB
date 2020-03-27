<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Impresora;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Ip;
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


        $equipo ->modelo=$request->get('modelo');

        $equipo ->fecha_registro=$dt;
        $equipo ->codigo=$request->get('codigo');
        $equipo ->tipo_equipo="impresora";
        $equipo ->descripcion=$request->get('descripcion');
        $equipo ->id_marca=$request->get('id_marca');
        $equipo ->asignado=$request->get('asignado');
        $equipo ->numero_serie=$request->get('numero_serie');
        $equipo ->estado_operativo=$request->get('estado_operativo');
        $equipo ->componente_principal=$request->get('componente_principal');
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
        return response()->json($impresora);

        /*Si el usuario elige una ip para la impresora, el
        estado de la ip debe cambiar a En uso */
        $id= $request->get('ip');
        if($id!==null){
            $ip= Ip::find($id);
            $ip->estado= "e";
            $ip->save();
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
        return Impresora::selectRaw('*, marcas.nombre as marca, empleados.nombre as empleado')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->join('empleados','asignado','=','cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->get();

    }
    
}
/*select('id_impresora','tipo','tinta','cinta', 'rodillo','rollo','toner',
        'cartucho','equipos.id_equipo','estado_operativo',
         'codigo','marcas.nombre as marca','modelo',
         'descripcion','numero_serie','encargado_registro',
          'equipos.created_at','asignado','departamento.nombre as dpto','bspi_punto') */