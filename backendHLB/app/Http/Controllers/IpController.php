<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ip;
use App\Models\Equipo;
use Illuminate\Support\Facades\DB;

class IpController extends Controller
{
    public function listar_ips()
    {
        return Ip::all();
    }

    public function buscar_ip_por_codigo($id_ip)
    {
        return Ip::select('*')
        ->where('id_ip',$id_ip)
        ->get();
        return Ip::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function crear_equipo_ip(Request $request)
    {
        // var_dump($request->get('registro_ip_obj'));
        // var_dump($request->get('registro_equipo_obj'));

        var_dump($request->get('registro_ip_obj')['estado']);

        DB::beginTransaction();
        try {
            // Primero creo la ip, y luego el equipo
            $ip = new Ip();
            $dt = new \DateTime();
            $dt->format('Y-m-d');

            $ip->estado = $request->get('registro_ip_obj')['estado'];
            $ip->fecha_asignacion = $dt;
            $ip->direccion_ip = $request->get('registro_ip_obj')['direccion_ip'];
            $ip->hostname = $request->get('registro_ip_obj')['hostname'];
            $ip->subred = $request->get('registro_ip_obj')['subred'];
            $ip->fortigate = $request->get('registro_ip_obj')['fortigate'];
            $ip->observacion = $request->get('registro_ip_obj')['observacion'];
            $ip->maquinas_adicionales = $request->get('registro_ip_obj')['maquinas_adicionales'];

            // Estos dos campos se guardan directamente aqui, en el backend debido a que maneja la sesion.
            $ip->nombre_usuario = 'Samuel Braganza';
            $ip->encargado_registro = 'admin';

            $ip->save();

            $equipo= new Equipo();
            // Aprovecho el id_ip saliente del insert anterior para referenciarlo en la tabla equipos
            $equipo->ip = $ip->id_ip;

            $equipo->fecha_registro = $request->get('registro_equipo_obj')['fecha_registro'];
            $equipo->estado_operativo  = $request->get('registro_equipo_obj')['estado_operativo'];
            $equipo->codigo = $request->get('registro_equipo_obj')['codigo'];
            $equipo->tipo_equipo = $request->get('registro_equipo_obj')['tipo_equipo'];
            $equipo->modelo = $request->get('registro_equipo_obj')['modelo'];
            $equipo->descripcion = $request->get('registro_equipo_obj')['descripcion'];
            $equipo->numero_serie = $request->get('registro_equipo_obj')['numero_serie'];
            $equipo->encargado_registro = $request->get('registro_equipo_obj')['encargado_registro'];
            $equipo->componente_principal = $request->get('registro_equipo_obj')['componente_principal'];

            $equipo->save();


            DB::commit();
            return response()->json(['status'=>'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => $e,
                ], 400);
        }
    }

    public function filtrar_ip($direccion_ip)
    {
        // $direccion_ip = $request->get('direccion_ip');
        return Ip::select('*')
            ->where('direccion_ip', 'like', "%" . $direccion_ip . "%")
            ->get();
    }
}
