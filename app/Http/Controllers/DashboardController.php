<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Solicitud;
use App\Models\Equipo;
use App\Models\Ip;
use App\Models\Marca;
use App\Models\ProgramaInstalado;
use Exception;


class DashboardController extends Controller
{
    public function obtener_numero_total_equipos() {
        $equipos = Equipo::where("estado_operativo", "<>", "B")->get();
        return $equipos->count();
    }

    public function obtener_numero_total_ips() {
        return Ip::SelectRaw('ips.*, bspi_punto, departamentos.nombre as departamento,
         empleados.nombre, empleados.apellido, equipos.codigo, equipos.tipo_equipo')
            ->leftjoin('equipos', 'id_ip', '=', 'equipos.ip')
            ->leftjoin('empleados', 'cedula', '=', 'asignado')
            ->leftjoin('departamentos', 'departamentos.id_departamento', '=', 'empleados.id_departamento')
            ->leftjoin('organizaciones', 'organizaciones.id_organizacion', '=', 'departamentos.id_organizacion')
            ->get()->count();
    }

    public function obtener_numero_total_marcas() {
        return Marca::all()->count();
    }

    public function obtener_numero_total_programas() {
        return ProgramaInstalado::all()->count();
    }

    public function mostrar_solicitudes_dashboard() {
        return Solicitud::SelectRaw('solicitudes.*, empleados.nombre, empleados.apellido')
            ->join('users', 'users.username', '=', 'solicitudes.id_usuario')
            ->join('empleados', 'empleados.cedula', '=', 'users.cedula')
            ->orderBy('solicitudes.fecha_realizacion', 'desc')
            ->orderBy('solicitudes.hora_realizacion', 'desc')->get();
    }
}
