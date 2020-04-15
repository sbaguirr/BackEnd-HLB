<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Router;
use App\Models\Equipo;
use App\Models\Ip;

class RouterController extends Controller
{
    public function listar_router()
    {
        return Router::select('routers.id_router', 'equipos.codigo', 'routers.nombre', 'routers.pass', 'routers.puerta_enlace', 'routers.usuario',
        'routers.clave', 'routers.id_equipo', 'marcas.id_marca', 'marcas.nombre as marca', 'equipos.id_equipo', 'equipos.modelo', 
        'equipos.numero_serie', 'equipos.estado_operativo', 'equipos.descripcion', 'departamentos.nombre as departamento',
        'organizaciones.bspi_punto', 'equipos.ip', 'empleados.nombre as nempleado', 'empleados.apellido')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->leftjoin('empleados','empleados.cedula', '=', 'equipos.asignado')
        ->leftjoin('departamentos', 'empleados.id_departamento', '=', 'departamentos.id_departamento')
        ->leftjoin('organizaciones', 'organizaciones.id_organizacion', '=', 'departamentos.id_organizacion')
        ->orderBy('routers.id_router', 'DESC')
        ->get();
    }

    public function buscar_router_por_id($id)
    {
      return Router::select('routers.nombre', 'routers.pass', 'routers.usuario', 'routers.clave', 
      'routers.puerta_enlace', 'equipos.id_marca', 'equipos.modelo', 'equipos.numero_serie',
      'equipos.estado_operativo', 'equipos.descripcion', 'equipos.ip', 'equipos.asignado')
      ->where('routers.id_router','=',$id)
      ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
      ->get();
    }

    public function crear_equipo_router(Request $request)
    {
        $equipo = new Equipo();
        $router = new Router();   

        $equipo->fecha_registro = $request->get('fecha_registro');
        $equipo->estado_operativo = $request->get('estado_operativo');
        $equipo->codigo = $request->get('codigo');
        $equipo->tipo_equipo = $request->get('tipo_equipo');
        $equipo->id_marca = $request->get('id_marca');
        $equipo->modelo = $request->get('modelo');
        $equipo->numero_serie = $request->get('numero_serie');
        $equipo->descripcion = $request->get('descripcion');
        $equipo->asignado = $request->get('asignado');
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
        $id = $request->get('ip');
        if($id!==null){
            $ip= Ip::find($id);
            $ip->estado= "EU";
            $ip->save();
        }   
        $router->save();    
    }

    public function marcas_routers(){
        return Router::select('marcas.id_marca', 'marcas.nombre')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->join('marcas','marcas.id_marca','=','equipos.id_marca')
        ->where('equipos.tipo_equipo','Router')
        ->distinct()
        ->get();
    }

    public function filtrar_routers($marca, $fecha_registro=null){
        $query= Router::select('routers.id_router', 'routers.nombre', 'routers.pass', 'routers.puerta_enlace', 'routers.usuario',
        'routers.clave', 'routers.id_equipo')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')  
        ->join('marcas','marcas.id_marca','=','equipos.id_marca');
        
        if($marca != "Todas" && !empty($fecha_registro)){
            $query= $query->where([['routers.created_at', 'like', "${fecha_registro}%"],
                ['marcas.nombre', '=', $marca]]);
        }
        if ($marca != "Todas" && empty($fecha_registro)){
            $query= $query->where('marcas.nombre', $marca);
        }
        if ($marca == "Todas" && !empty($fecha_registro)){
            $query= $query->whereDate('routers.created_at', $fecha_registro);
        }
        return  $query->get();
    }

    public function buscar_router($codigo){
        return Router::select('equipos.codigo','routers.id_router', 'routers.nombre', 'routers.pass', 'routers.puerta_enlace', 'routers.usuario',
        'routers.clave', 'routers.id_equipo')
        ->join('equipos','equipos.id_equipo','=','routers.id_equipo')
        ->where('equipos.codigo','like',"%".strtolower($codigo)."%")
        ->orderBy('equipos.created_at', 'desc')
        ->get();
    }

    public function eliminar_router($id)
    {
      $router = Router::find($id);
      $equipo = Equipo::find($router->id_equipo);
      if ($equipo->estado_operativo !== 'B'){
        $equipo->estado_operativo = 'B';
        $equipo->save();
      }else{
        return response()->json(['message' => 'Imposible eliminar. El registro ya ha sido dado de baja'], 400);
      }      
    }

    public function editar_equipo_router(Request $request)
    {
      $router = Router::find($request->id_equipo);
      $equipo = Equipo::find($router->id_equipo);   
      $ip_anterior= $equipo->ip; 
      $equipo->fecha_registro = $request->get('fecha_registro');      
      $equipo->estado_operativo = $request->get('estado_operativo');
      $equipo->codigo = $request->get('codigo');
      $equipo->tipo_equipo = $request->get('tipo_equipo');
      $equipo->id_marca = $request->get('id_marca');
      $equipo->modelo = $request->get('modelo');
      $equipo->numero_serie = $request->get('numero_serie');
      $equipo->descripcion = $request->get('descripcion');
      $equipo->asignado = $request->get('asignado');
      $equipo->encargado_registro = $request->get('encargado_registro');
      $equipo->componente_principal = $request->get('componente_principal');
      
      $ip_actual = $request->get('ip');
        if($ip_actual!==null){
            if($ip_anterior!==$ip_actual){
                $ip= Ip::find($ip_actual);
                $ip->estado= "EU";
                $ip->save();
            }
        }else{
            $ip_actual=null;
        }
      
        $equipo->ip = $request->get('ip'); 
        if($ip_anterior!==null){
            $anterior= Ip::find($ip_anterior);
            $anterior->estado= "L";
            $anterior->save();
        }
      $equipo->save(); 

      $router->nombre = $request->get('nombre');
      $router->pass = $request->get('pass');
      $router->puerta_enlace = $request->get('puerta_enlace');
      $router->usuario = $request->get('usuario');
      $router->clave = $request->get('clave');
      $router->save();   
      
      
    }

}
