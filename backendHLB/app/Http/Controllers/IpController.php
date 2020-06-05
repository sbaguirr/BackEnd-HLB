<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ip;
use App\Models\Equipo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class IpController extends Controller
{
    public function listar_ips()
    {
        // return Ip::all();
        $users = DB::table('ips')->paginate(10);
        return $users;
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
        DB::beginTransaction();

        try {
            $ip = new Ip();
            $dt = new \DateTime();
            $dt->format('Y-m-d');

            $ip->direccion_ip = $request->get('direccion_ip');
            $ip->hostname = $request->get('hostname');
            $ip->subred = $request->get('subred');
            $ip->estado = $request->get('estado');
            $ip->fortigate = $request->get('fortigate');
            $ip->observacion = $request->get('observacion');
            $ip->maquinas_adicionales = $request->get('maquinas_adicionales');
            // $ip->fecha_asignacion = $dt;

            // Estos dos campos se guardan directamente aqui, en el backend debido a que maneja la sesion.
            $ip->nombre_usuario = 'Samuel Braganza';
            $ip->encargado_registro = 'admin';

            // Estos dos campos se guardan directamente aqui, en el backend debido a que maneja la sesion.
            $ip->nombre_usuario = 'Samuel Braganza';
            $ip->encargado_registro = 'admin';

            $ip->save();
            
            DB::commit();

            return Response::json (
                array (
                    'success' => true,
                    'id_ip' => $ip->id_ip,
                )
            , 200);

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

    

    public function ips_libres()
    {
      return Ip::select('id_ip', 'direccion_ip')
      ->where('estado','=','L')
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
        $ip= Ip::find($request->get('key'));
        $ip->direccion_ip=$request->get('direccion_ip');
        $ip->hostname=$request->get('hostname');
        $ip->subred=$request->get('subred');
        $ip->estado=$request->get('estado');
        $ip->fortigate=$request->get('fortigate');
        $ip->observacion=$request->get('observacion');
        $ip->maquinas_adicionales=$request->get('maquinas_adicionales');
        $ip->nombre_usuario=$request->get('nombre_usuario');
        $ip->encargado_registro=$request->get('encargado_registro');
        $ip->save();
    }

    public function es_ip_enuso($ip){
        $reg_ip = Ip::find($ip);
        if($reg_ip->estado === 'EU'){
            return $reg_ip->direccion_ip;
        }
    }

    /* Servicio para obtener datos de la ip a partir de su ID */
    public function ip_id($id_ip){
        return Ip::SelectRaw('ips.*, bspi_punto, departamentos.nombre as departamento,
         empleados.nombre, empleados.apellido, equipos.codigo, equipos.tipo_equipo')
        ->leftjoin('equipos','id_ip','=','equipos.ip')
        ->leftjoin('empleados','cedula','=','asignado')
        ->leftjoin('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->leftjoin('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('ips.id_ip',$id_ip)
        ->get();
    }

 
    public function eliminar_ip($id_ip){
        try{
             # Elimino la Ip
            $ip= Ip::find($id_ip);
            $ip->delete();
            return response()->json(['log'=>'Registro eliminado satisfactoriamente'],200); 
        }catch(Exception $e){
            return response()->json(['log'=>$e],400);
        }
}


}