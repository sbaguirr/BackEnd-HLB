<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Router;
use App\Models\Equipo;

class RouterController extends Controller
{
    public function listar_router()
    {
        return Router::all();
    }

    public function crear_equipo_router(Request $request)
    {
        $equipo = new Equipo();
        $router = new Router();     
        $equipo->fecha_registro = $request->get('fecha_registro');
        $equipo->estado_operativo = $request->get('estado_operativo');
        $equipo->codigo = $request->get('codigo');
        $equipo->tipo_equipo = $request->get('tipo_equipo');
        $equipo->marca = $request->get('marca');
        $equipo->modelo = $request->get('modelo');
        $equipo->numero_serie = $request->get('numero_serie');
        $equipo->descripcion = $request->get('descripcion');
        $equipo->encargado_registro = $request->get('encargado_registro');
        $equipo->componente_principal = $request->get('componente_principal');
        $equipo->ip = $request->get('ip');
        $equipo->save();  
        $id_equip = $equipo->id_equipo;
        $router->id_equipo = $id_equip;
        $router->nombre = $request->get('nombre');
        $router->pass = $request->get('pass');
        $router->puerta_enlace = $request->get('puerta_enlace');
        $router->usuario = $request->get('usuario');
        $router->clave = $request->get('clave');
        $router->save();        
    }

    public function marcas_routers(){
        return Router::select('equipos.marca')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->where('equipos.tipo_equipo','Router')
        ->where('equipos.marca','!=','')
        ->distinct()
        ->get();
    }
}
