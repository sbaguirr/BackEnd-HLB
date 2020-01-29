<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Impresora;
use App\Models\Equipo;
use DateTime;


class ImpresoraController extends Controller
{
    //

    public function crear_impresora(Request $request){
        $impresora = new Impresora();
        $equipo = new Equipo();
        $dt = new \DateTime();
        $dt->format('Y-m-d');
        $equipo ->marca=$request->get('marca');
        $equipo ->modelo=$request->get('modelo');
        $equipo ->fecha_registro=$dt;
        $equipo ->codigo=$request->get('codigo');
        $equipo ->tipo_equipo=$request->get('tipo');
        $equipo ->descripcion=$request->get('descripcion');

        $equipo ->encargado_registro='admin';
        $equipo ->numero_serie=$request->get('numero_serie');
        $equipo ->estado_operativo=$request->get('estado_operativo');
        //$equipo ->id_equipo=$request->get('id_equipo');
        $equipo->save();


        /*
        $impresora ->tipo=$request->input('tipo');
        $impresora ->marca=$request->input('marca');
        $impresora ->modelo=$request->input('modelo');
        $impresora ->numero_serie=$request->input('numero_serie');
        $impresora ->tinta=$request->input('tinta');
        $impresora ->cartucho=$request->input('cartucho');
        $impresora ->estado_operativo=$request->input('estado_operativo');
        $impresora ->id_equipo=$request->input('id_equipo');
        */

        /*
        $impresora ->tipo=$request->get('tipo');
        $impresora ->marca=$request->get('marca');
        $impresora ->modelo=$request->get('modelo');
        $impresora ->numero_serie=$request->get('numero_serie');
        $impresora ->tinta=$request->get('tinta');
        $impresora ->cartucho=$request->get('cartucho');
        $impresora ->estado_operativo=$request->get('estado_operativo');
        $impresora ->id_equipo=$request->get('id_equipo');
        */

        $id=$equipo->id_equipo;
        $impresora ->tipo=$request->get('tipo');
        $impresora ->tinta=$request->get('tinta');
        $impresora ->cartucho=$request->get('cartucho');
        //$impresora ->id_equipo=(int)$request->get('id_equipo');
        $impresora ->id_equipo=$id;

        $impresora->save();
        return response()->json($impresora);



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
        /*
        $impresoras = Impresora::select('id_impresora','tipo','tinta','cartucho','id_equipo','estado_operativo','codigo','marca','modelo','descripcion','numero_serie','encargado_registro')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_impresora')
        ->get();
        */
        //return response()->json($impresoras);
        return Impresora::select('id_impresora','tipo','tinta','cartucho','equipos.id_equipo','estado_operativo','codigo','marca','modelo','descripcion','numero_serie','encargado_registro')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_equipo')
        ->get();

    }

    public function marcas_impresoras(){
        return Impresora::select('marca')
        ->join('equipos','equipos.id_equipo','=','impresoras.id_impresora')
        ->distinct()
        ->get();
    }

}
