<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\QueryException;
use Exception;

class IpController extends Controller
{
    public function listar_ips()
    {
        // return Ip::all();
        $ips = Ip::SelectRaw('ips.*,
        empleados.nombre, empleados.apellido')
            ->leftjoin('equipos', 'id_ip', '=', 'equipos.ip')
            ->leftjoin('empleados', 'cedula', '=', 'asignado')
            ->paginate(10);
        return $ips;
    }

    public function mostrar_ips()
    {
        return Ip::all();
    }

    public function mostrar_ips_detalladas()
    {
        return Ip::SelectRaw('ips.*, bspi_punto, departamentos.nombre as departamento,
         empleados.nombre, empleados.apellido, equipos.codigo, equipos.tipo_equipo')
            ->leftjoin('equipos', 'id_ip', '=', 'equipos.ip')
            ->leftjoin('empleados', 'cedula', '=', 'asignado')
            ->leftjoin('departamentos', 'departamentos.id_departamento', '=', 'empleados.id_departamento')
            ->leftjoin('organizaciones', 'organizaciones.id_organizacion', '=', 'departamentos.id_organizacion')
            ->get();
    }


    public function listar_ips_prueba()
    {
        // $ips = Ip::all();

        // $result = array();

        // foreach ($ips as $ip)
        // {
        //     $result = (object) [
        //         'direccion_ip' => $ip->direccion_ip,
        //         'estado' => $ip->estado->nombre,
        //     ];
        // }

        // $response = array(
        //     'status' => true,
        //     'message' => 'Success',
        //     'data' => $result
        // );
        // return $response;

        return Ip::find(1)->estado->nombre;
    }

    private function verificar_ip_ya_existe($direccion_ip)
    {
        $buscar_ip = Ip::select('*')
            ->where('direccion_ip', $direccion_ip)
            ->get();
        return isset($buscar_ip->id_ip);
    }

    public function buscar_ip_por_codigo($id_ip)
    {
        return Ip::select('*')
            ->where('id_ip', $id_ip)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function crear_ip(Request $request)
    {
        try {
            $ip = new Ip();
            $ip->direccion_ip = $request->get('direccion_ip');
            $ip->hostname = $request->get('hostname');
            $ip->subred = $request->get('subred');
            $ip->estado = "L";
            $ip->fortigate = $request->get('fortigate');
            $ip->observacion = $request->get('observacion');
            $ip->maquinas_adicionales = $request->get('maquinas_adicionales');
            $ip->encargado_registro = $request->get('encargado_registro');
            $ip->save();
            return Response::json(
                array(
                    'success' => true,
                    'id_ip' => $ip->id_ip,
                ),
                200
            );
        } catch (QueryException $e) {
            DB::rollback();
            $error_code = $e->errorInfo[1];
            if ($error_code == 1062) {
                return response()->json([
                    'status' => 'error',
                    'log' => 'La direccion IP que ha ingresado ya existe. Por favor ingrese una direccion IP que no exista.'
                ], 409);
            }
            return response()->json([
                'status' => 'error',
                'log' => $e
            ], 409);
        }
    }

    public function filtrar_ip($direccion_ip)
    {
        // $direccion_ip = $request->get('direccion_ip');
        return Ip::select('*')
            ->where('direccion_ip', 'like', "%" . $direccion_ip . "%")
            ->get();
    }



    public function ips_libres()
    {
        return Ip::select('id_ip', 'direccion_ip')
            ->where('estado', '=', 'L')
            ->get();
    }

    public function ip_asignada($id_ip)
    {
        $ip = Ip::find($id_ip);
        $ip->estado = 'EU';
        $ip->save();
    }

    public function editar_ip(Request $request)
    {
        try {
            $ip = Ip::find($request->get('key')); #key es el id de la ip.
            $ip->direccion_ip = $request->get('direccion_ip');
            $ip->hostname = $request->get('hostname');
            $ip->subred = $request->get('subred');
            $ip->fortigate = $request->get('fortigate');
            $ip->observacion = $request->get('observacion');
            $ip->maquinas_adicionales = $request->get('maquinas_adicionales');
            $ip->encargado_registro = $request->get('encargado_registro');
            $ip->save();
        } catch (QueryException $e) {
            $error_code = $e->errorInfo[1];
            if ($error_code == 1062) {
                return response()->json(['log' => 'La IP ingresada ya existe'], 500);
            }
            return response()->json(['log' => $e], 500);
        }
    }

    public function es_ip_enuso($ip)
    {
        $reg_ip = Ip::find($ip);
        if ($reg_ip->estado === 'EU') {
            return $reg_ip->direccion_ip;
        }
    }

    public function Ip_ID_Only($id)
    {
        $list_ip = Ip::select('id_ip', 'direccion_ip')->where("estado", "=", "L");
        if ($id != null || $id != "" || $id != -1) {
            $list_ip =  $list_ip->orWhere("id_ip", "=", $id);
        }

        return response()->json($list_ip->get());
    }

    /* Servicio para obtener datos de la ip a partir de su ID */
    public function ip_id($id_ip)
    {
        return Ip::SelectRaw('ips.*, bspi_punto, departamentos.nombre as departamento,
         empleados.nombre, empleados.apellido, equipos.codigo, equipos.tipo_equipo')
            ->leftjoin('equipos', 'id_ip', '=', 'equipos.ip')
            ->leftjoin('empleados', 'cedula', '=', 'asignado')
            ->leftjoin('departamentos', 'departamentos.id_departamento', '=', 'empleados.id_departamento')
            ->leftjoin('organizaciones', 'organizaciones.id_organizacion', '=', 'departamentos.id_organizacion')
            ->where('ips.id_ip', $id_ip)
            ->get();
    }


    public function eliminar_ip($id_ip)
    {
        try {
            # Elimino la Ip
            $ip = Ip::find($id_ip);
            $ip->delete();
            return response()->json(['log' => 'Registro eliminado satisfactoriamente'], 200);
        } catch (Exception $e) {
            return response()->json(['log' => $e], 400);
        }
    }
}
