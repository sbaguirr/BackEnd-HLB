<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Equipo;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class MantenimientoController extends Controller
{

    public function crear_mantenimiento(Request $request)
    {
        try {
            $this->validar_datos($request);
            $id_equipo = Equipo::select('id_equipo')->where('codigo', $request->get('codigo'))->get()[0];
            $inicio = $request->get('fecha_inicio');
            $fin = $request->get('fecha_fin');
            $this->comparar_fechas($inicio, $fin, "La fecha de finalización");
            DB::beginTransaction();
            $mantenimiento = new Mantenimiento();
            $this->almacenar_mantenimiento($request, $mantenimiento, $inicio, $fin, $id_equipo);
            $fecha_recordatorio = $request->get('fecha_recordatorio');
            $hora_recordatorio = $request->get('hora_recordatorio');
            $this->crear_recordatorio($fecha_recordatorio, $hora_recordatorio, $inicio, $mantenimiento->id_mantenimiento);
            DB::commit();
        } catch (Exception $error) {
            DB::rollback();
            return response()->json(['log' => $error->getMessage()], 400);
        }
    }

    private function almacenar_mantenimiento($request, $mantenimiento, $inicio, $fin, $id_equipo)
    {
        $mantenimiento->titulo = $request->get('titulo');
        $mantenimiento->tipo = $request->get('tipo');
        $mantenimiento->fecha_inicio = $inicio;
        $mantenimiento->fecha_fin = $fin;
        $mantenimiento->observacion_falla = $request->get('observacion_falla');
        $mantenimiento->estado_fisico = $request->get('estado_fisico');
        $mantenimiento->actividad_realizada = $request->get('actividad_realizada');
        $mantenimiento->observacion = $request->get('observacion');
        $mantenimiento->id_equipo = $id_equipo->id_equipo;
        $mantenimiento->realizado_por = $request->get('realizado_por');
        $mantenimiento->save();
    }

    public function editar_mantenimiento(Request $request)
    {
        try {
            $this->validar_datos($request);
            $id_equipo = Equipo::select('id_equipo')->where('codigo', $request->get('codigo'))->get()[0];
            $inicio = $request->get('fecha_inicio');
            $fin = $request->get('fecha_fin');
            $this->comparar_fechas($inicio, $fin, "La fecha de finalización");
            DB::beginTransaction();
            $mantenimiento = Mantenimiento::find($request->get('id_mantenimiento'));
            $this->almacenar_mantenimiento($request, $mantenimiento, $inicio, $fin, $id_equipo);
            $fecha_recordatorio = $request->get('fecha_recordatorio');
            $hora_recordatorio = $request->get('hora_recordatorio');
            $this->editar_recordatorio($fecha_recordatorio, $hora_recordatorio, $inicio, $mantenimiento->id_mantenimiento);
            DB::commit();
        } catch (Exception $error) {
            DB::rollback();
            return response()->json(['log' => $error->getMessage()], 400);
        }
    }


    private function crear_recordatorio($f, $h, $fi, $id)
    {
        if (!empty($f) && !empty($h)) {
            $this->comparar_fechas($fi, $f, "La fecha del recordatorio");
            $recordatorio = new Recordatorio();
            $recordatorio->fecha_recordatorio = $f;
            $recordatorio->hora_recordatorio = $h;
            $recordatorio->estado = 'A';
            $recordatorio->id_mantenimiento = $id;
            $recordatorio->save();
        }
    }

    private function editar_recordatorio($f, $h, $fi, $id)
    {
        if (!empty($f) && !empty($h)) {
            $this->comparar_fechas($fi, $f, "La fecha del recordatorio");
            Recordatorio::Where('id_mantenimiento', '=', $id)
                ->update(['fecha_recordatorio' => $f, 'hora_recordatorio' => $h]);
        }
    }

    public function mostrar_mantenimientos(Request $request)
    {

        $codigo_equipo = $request->get("codigo_equipo");


        $query = Mantenimiento::select(
            'mantenimientos.id_mantenimiento',
            'equipos.codigo',
            'mantenimientos.tipo',
            'titulo',
            'realizado_por',
            'equipos.id_equipo',
            'fecha_inicio',
            'estado_operativo',
            'tipo_equipo',
            'codigo'
        )
            ->join('equipos', 'equipos.id_equipo', '=', 'mantenimientos.id_equipo')
            ->where('equipos.codigo', '=', $codigo_equipo);

        $itemSize = $query->count();
        $query->orderBy('mantenimientos.created_at', 'desc');
        $query = $query->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index"));
        return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
    }

    public function mantenimiento_id($id)
    {
        return Mantenimiento::selectRaw('mantenimientos.*, equipos.codigo, recordatorios.hora_recordatorio
        ,recordatorios.fecha_recordatorio')
            ->join('equipos', 'equipos.id_equipo', '=', 'mantenimientos.id_equipo')
            ->leftjoin('recordatorios', 'mantenimientos.id_mantenimiento', '=', 'recordatorios.id_mantenimiento')
            ->where('mantenimientos.id_mantenimiento', $id)
            ->get();
    }

    private function comparar_fechas($a, $b, $mensaje)
    {
        $inicio = Carbon::parse($a);
        $fin = Carbon::parse($b);
        if ($fin->lessThan($inicio)) {
            throw new Exception($mensaje . " no puede ser anterior a la fecha de inicio");
        }
    }

    private function validar_datos($request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string',
            'tipo' => 'required',
            'fecha_inicio' => 'required'
        ]);
        if ($validator->fails()) {
            throw new Exception("Debe completar los campos requeridos");
        }
    }

    public function equipos_por_codigo(Request $request)
    {
        $codigo = $request->get("codigo");
        if (!empty($codigo) && !is_null($codigo)) {
            $query = Equipo::select('codigo', 'tipo_equipo', 'estado_operativo')
                ->where('equipos.codigo', 'like', "%" . strtolower($codigo) . "%");
            $itemSize = $query->count();
            $query = $query->limit($request->get("page_size"))->offset($request->get("page_size") * $request->get("page_index"));
            return response()->json(["resp" => $query->get(), "itemSize" => $itemSize])->header("itemSize", $itemSize);
        } else {
            return response()->json(["resp" => [], "itemSize" => 0])->header("itemSize", 0);
        }
    }

    public function eliminar_mantenimiento($id_mantenimiento)
    {
        try {
            # Elimino el recordatorio asociado
            $rec = Recordatorio::where('id_mantenimiento', $id_mantenimiento);
            $rec->delete();
            $mant = Mantenimiento::find($id_mantenimiento);
            $mant->delete();
        } catch (Exception $e) {
            return response()->json(['log' => "Ocurrió un error al eliminar el mantenimiento, inténtelo más tarde"], 400);
        }
    }

        /* Recordatorios */
        public function mostrar_recordatorios($size)
        {
            return Mantenimiento::selectRaw('recordatorios.id_recordatorio,
                                             recordatorios.hora_recordatorio,
                                             recordatorios.fecha_recordatorio,
                                             recordatorios.estado,
                                             recordatorios.id_mantenimiento,
                                             recordatorios.created_at,
                                             mantenimientos.titulo,
                                             mantenimientos.id_equipo,
                                             equipos.codigo,
                                             equipos.tipo_equipo,
                                             equipos.estado_operativo')
                ->join('equipos', 'equipos.id_equipo', '=', 'mantenimientos.id_equipo')
                ->join('recordatorios', 'recordatorios.id_mantenimiento', '=', 'mantenimientos.id_mantenimiento')
                ->where('recordatorios.estado', 'A')
                ->orderBy('recordatorios.fecha_recordatorio', 'asc')
                ->paginate($size);
        }

        public function eliminar_recordatorio($id,Request $request){
            DB::beginTransaction();
            try {
                //Recordatorio::Where('id_recordatorio', '=', $id)->update(['estado' => 'I']);
                $rec = Recordatorio::where('id_recordatorio', $id);
                $rec->delete();
                DB::commit();
                if ($request->get('tipo')==='general'){
                    return $this-> mostrar_recordatorios($request->get('size'));
                }
                if ($request->get('tipo')==='codigo'){
                    return $this-> impresoras_codigo_paginado($request->get('codigo'),$request->get('size'));
                }
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['log' => -1], 400);
            }
        }

        public function recordatorio_codigo($codigo){
            return Mantenimiento::selectRaw('recordatorios.id_recordatorio,
                                             recordatorios.hora_recordatorio,
                                             recordatorios.fecha_recordatorio,
                                             recordatorios.estado,
                                             recordatorios.id_mantenimiento,
                                             recordatorios.created_at,
                                             mantenimientos.titulo,
                                             equipos.codigo')
                ->join('equipos', 'equipos.id_equipo', '=', 'mantenimientos.id_equipo')
                ->join('recordatorios', 'recordatorios.id_mantenimiento', '=', 'mantenimientos.id_mantenimiento')
                ->where('recordatorios.estado', 'A')
                ->where('equipos.codigo','like',"%".$codigo."%")
                ->orderBy('recordatorios.fecha_recordatorio', 'asc')
                ->get();
        }

}
