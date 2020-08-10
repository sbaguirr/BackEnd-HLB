<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Correo;
use App\Models\Ip;
use App\Models\Marca;
use App\Models\Empleado;
use App\Models\Departamento;
use App\Models\Organizacion;
use App\Models\DetalleComponente;
use App\Models\DetalleEquipo;
use App\Models\ProgramaInstalado;
use App\Models\ProgramaEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Database\QueryException;
use DateTime;

class ImportController extends Controller
{


    private function empByCedula($cedula)
    {
        $result = Empleado::Where('cedula', '=', trim($cedula))->get();
        if (count($result)==0) {
            return ['err' => 'El Empleado asignado no esta registrado.'];
        }
        if (count($result) > 1) {
            return ['err' => 'Existe mas de un empleado con esa identificacion.'];
        }
        return ['cedula' => ($result[0]->cedula)];
    }

    private function validarEmpAsig($cedula){
        if(empty($cedula)){
            return ['err' => 'Debe ingresar una cedula valida de un empleado.'];
        }
        return $this->empByCedula($cedula);
    }

    private function eqByCodigo($codigo)
    {

        if (empty($codigo)) {
            return ['err' => 'Debe ingresar el codigo para registrar el equipo.'];
        }
        $codigo = strtoupper(trim($codigo));
        $result = Equipo::Where('codigo', '=', $codigo)->get();
        if (count($result) > 0) {
            return ['err' => 'Ya existe un equipo registrado con ese codigo.'];
        }
        return ['codigo' => $codigo];
    }

    private function compPrinByCod($comp)
    {
        $codigo = strtoupper(trim($comp));
        $result = Equipo::Where('codigo', '=', $codigo)->get();
        if (count($result)==0) {
            return ['err' => 'El componente principal ingresado no existe.'];
        }
        return ['comp' => ($result[0]->id_equipo)];
    }

    private function getEstado($estado)
    {
        if (empty($estado)) {
            return ['err' => 'Debe ingresar el estado del equipo.'];
        }
        $estado = strtolower(trim($estado));
        $lisEst = [
            'operativo' => 'O', 'o' => 'O', 'de baja' => 'B', 'b' => 'B', 'disponible' => 'D', 'd' => 'D', 'en revision' => 'ER', 'er' => 'ER',
            'reparado' => 'R', 'r' => 'R'
        ];
        if (!array_key_exists($estado, $lisEst)) {
            return ['err' => 'El estado del equipo ingresado no es valido.'];
        }
        return ['estado' => $lisEst[$estado]];
    }

    private function getTipoEq($tipo)
    {
        if (empty($tipo)) {
            return ['err' => 'Debe ingresar el estado del equipo.'];
        }

        $tipo = strtolower(trim($tipo));
        $listTipo = [
            'parlantes' => 'Parlantes', 'monitor' => 'Monitor', 'teclado' => 'Teclado', 'cpu' => 'CPU', 'case' => 'case', 'disco duro' => "disco_duro",
            'fuente de poder' => 'fuente_poder', 'fuente poder' => "fuente_poder", 'memoria ram' => 'memoria_ram', 'mouse' => 'Mouse', 'procesador' => 'Procesador', 'regulador' => 'Regulador',
            'ups' => 'UPS', 'trajeta de red' => 'tarjeta_red', 'tarjeta red' => 'tarjeta_red', 'fuentepoder' => "fuente_poder", 'memoriaram' => 'memoria_ram', 'discoduro' => "disco_duro",
            'tarjetared' => 'tarjeta_red', 'tarjeta madre' => 'tarjeta_madre', 'tarjetamadre' => 'tarjeta_madre', 'mainboard' => 'tarjeta_madre', 'main board' => 'tarjeta_madre'
        ];

        if (array_key_exists($tipo, $listTipo)) {
            return ['tipo_equipo' => $listTipo[$tipo]];
        }
        return ['tipo_equipo' => str_replace(' ', '_', ($tipo))];
    }

    private function getMarcaByNomb($marca)
    {
        if (empty($marca)) {
            return ['err' => 'Debe ingresar la arca del equipo.'];
        }
        $result = Marca::select('id_marca')->where('nombre', '=', strtolower(trim($marca)))->get();
        if (count($result)==0) {
            return ['err' => 'La marca ingresa no existe. Debe registrarla.'];
        }
        return ['id_marca' => ($result[0]->id_marca)];
    }

    private function getIPByDir($ip)
    {
        $ip = trim($ip);
        $result = IP::Where('direccion_ip', '=', $ip)->get();
        if (count($result)==0) {
            return ['err' => 'La direccion IP ingresada no existe. Debe registrarla.'];
        }
        if ($result[0]->estado == 'EU') {
            return ['err' => 'La direccion IP ingresada ya esta en uso.'];
        }
        return ['id_ip' => $result[0]->id_ip];
    }

    private function tipoAlm($tipoEq, $alm)
    {
        if (empty($alm)) {
            return ['err' => 'Debe ingresar un tipo de almacenamiento para este tipo de equipo.'];
        }
        $alm = strtoupper(trim($alm));
        $listDisk = ['SSD', 'HDD'];
        $listRam = ['DDR', 'DDR2', 'DDR3', 'DDR4'];
        if ($tipoEq == 'disco_duro' && !in_array($alm, $listDisk)) {
            return ['err' => 'El tipo de almacenamiento ingresado no es valido. Disco Duro: SSD - HDD.'];
        }
        if ($tipoEq == 'memoria_ram' && !in_array($alm, $listRam)) {
            return ['err' => 'El tipo de almacenamiento ingresado no es valido. Memoria RAM: DDR - DDR2 - DDR3 - DDR4.'];
        }
        return ['tipoAlm' => $alm];
    }

    private function capacidadAlm($tipoEq, $alm)
    {
        if (empty($alm)) {
            return ['err' => 'Debe ingresar '.($tipoEq == 'tarjeta_madre' ? 'la Ram Soportada':'una capacidad almacenamiento').' para este tipo de equipo.'];
        }
        $alm = strtoupper(trim($alm));
        $l_alm = explode(' ', $alm);
        $listTA = ['MB', 'GB', 'TB'];
        if (count($l_alm) != 2) {
            return ['err' => 'Debe ingresar una '.($tipoEq == 'tarjeta_madre' ? 'Ram Soportada':'capacidad almacenamiento').' valida. Ejemplo: 2 GB'];
        }
        if (!is_numeric($l_alm[0]) || !in_array($l_alm[1], $listTA)) {
            return ['err' => 'Debe ingresar una '.($tipoEq == 'tarjeta_madre' ? 'Ram Soportada':'capacidad almacenamiento').' valida. Ejemplo: 2 GB'];
        }
        return ['capAlm' => ($l_alm[1]=='MB'?str_replace('MB','Mb',$alm):$alm)];
    }

    private function validarHeaders($headers, $obj){
        $index = 0;
        $resp = '';
        while($resp=='' && $index < count($headers)){
            if(!array_key_exists($headers[$index], $obj)){
                $resp = 'El documento no es valido, la columna "'.$headers[$index].'" no se encuentra. Descargue el formato guia desde la plataforma.';
            }
            $index++;
        }
        return $resp;
    }

    private function validarHeadersEquipos($obj){
        $headers = ['Empleado', 'Codigo', 'Marca', 'Modelo', 'Numero de Serie',
        'Estado', 'Tipo', 'IP', 'Capacidad Almacenamiento', 'Tipo Almacenamiento', 'Numero de Slots RAM', 'RAM Soportada',
        'Conexiones para Discos','Nucleos', 'Frecuencia', 'Componente Principal', 'Descripcion'];
        return $this->validarHeaders($headers, $obj);
    }

    private function validarHeadersCorreo($obj){
        $headers = ['Empleado', 'Correo', 'Pass'];
        return $this->validarHeaders($headers, $obj);
    }

    private function validarCorreo($correo){
        if(empty($correo)){
            return ['err'=>'Debe ingresar un correo para el registro.'];
        }
        if(!filter_var($correo,FILTER_VALIDATE_EMAIL)){
            return ['err' => 'El correo ingresado no es valido.'];
        }
        $correo = trim($correo);
        $emails = Correo::Where('correo','=',$correo)->get();
        if(count($emails)>0){
            return ['err' => 'El correo ingresado ya existe. Ingrese uno Nuevo.'];
        }
        return ['correo' => $correo];
    }


    public function reg_masivo_equipos(Request $request)
    {
        $data = $request->get('data');
        $resp = array();
        $respSuccess = array();
        for ($i = 0; $i < count($data); $i++) {
            $obj = $data[$i];

            $headers = $this->validarHeadersEquipos($obj);
            if($headers!=''){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $headers, 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            $tipoEq = $this->getTipoEq($obj['Tipo']);
            if (array_key_exists('err', $tipoEq)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $tipoEq['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $tipoEq = $tipoEq['tipo_equipo'];
            }

            $emp = null;
            if (!empty($obj['Empleado'])) {
                $emp = $this->empByCedula($obj['Empleado']);
                if (array_key_exists('err', $emp)) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $emp['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                } else {
                    $emp = $emp['cedula'];
                }
            }

            $eq = $this->eqByCodigo($obj['Codigo']);
            if (array_key_exists('err', $eq)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $eq['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $eq = $eq['codigo'];
            }

            $estado = $this->getEstado($obj['Estado']);
            if (array_key_exists('err', $estado)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $estado['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $estado = $estado['estado'];
            }

            $marca = $this->getMarcaByNomb($obj['Marca']);
            if (array_key_exists('err', $marca)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $estado['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $marca = $marca['id_marca'];
            }

            if (empty($obj['Modelo'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            if (empty($obj['Numero de Serie'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            $ip = null;
            if (!empty($obj['IP'])) {
                $ip = $this->getIPByDir($obj['IP']);
                if (array_key_exists('err', $ip)) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $ip['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                } else {
                    $ip = $ip['id_ip'];
                }
            }

            $comp = null;
            if (!empty($obj['Componente Principal'])) {
                $comp = $this->compPrinByCod($obj['Componente Principal']);
                if (array_key_exists('err', $comp)) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $comp['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                } else {
                    $comp = $comp['id_equipo'];
                }
            }

            DB::beginTransaction();
            try {
                $equipo = new Equipo();
                $dt = new \DateTime();
                $dt->format('Y-m-d');
                $equipo->codigo = $eq;
                $equipo->modelo = trim($obj['Modelo']);
                $equipo->fecha_registro = $dt;
                $equipo->descripcion = trim($obj['Descripcion']);
                $equipo->id_marca = $marca;
                $equipo->asignado = $emp;
                $equipo->tipo_equipo = $tipoEq;
                $equipo->numero_serie = trim($obj['Numero de Serie']);
                $equipo->estado_operativo = $estado;
                $equipo->componente_principal = $comp;
                $equipo->encargado_registro = $request->get('encargado_registro');
                $equipo->ip = $ip;

                if ($ip !== null) {
                    $ip_ = Ip::find($ip);
                    $ip_->estado = "EU";
                    $ip_->save();
                }
                $equipo->save();

                if (
                    strcasecmp($equipo->tipo_equipo, "memoria_ram") == 0 ||
                    strcasecmp($equipo->tipo_equipo, "disco_duro") == 0
                ) {
                    $tipoAlm = $this->tipoAlm($equipo->tipo_equipo, $obj['Tipo Almacenamiento']);
                    if (array_key_exists('err', $tipoAlm)) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $tipoAlm['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    } else {
                        $tipoAlm = $tipoAlm['tipoAlm'];
                    }
                    $capAlm = $this->capacidadAlm($equipo->tipo_equipo, $obj['Capacidad Almacenamiento']);
                    if (array_key_exists('err', $capAlm)) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $capAlm['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    } else {
                        $capAlm = $capAlm['capAlm'];
                    }
                    $tipo = new DetalleComponente();
                    $tipo->campo = 'tipo';
                    $tipo->dato = $tipoAlm;
                    $tipo->id_equipo = $equipo->id_equipo;
                    $tipo->save();

                    $capacidad = new DetalleComponente();
                    $capacidad->campo = 'capacidad';
                    $capacidad->dato = $capAlm;
                    $capacidad->id_equipo = $equipo->id_equipo;
                    $capacidad->save();
                } else if (strcasecmp($equipo->tipo_equipo, "procesador") == 0) {
                    if (!is_numeric($obj['Nucleos'])) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un numero de nucleos valido para el procesador', 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    }
                    if (!is_numeric($obj['Frecuencia'])) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar una frecuencia valida para el procesador', 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    }
                    $nucleos = new DetalleComponente();
                    $nucleos->campo = 'nucleos';
                    $nucleos->dato = trim($obj['Nucleos']);
                    $nucleos->id_equipo = $equipo->id_equipo;
                    $nucleos->save();

                    $frec = new DetalleComponente();
                    $frec->campo = 'frecuencia';
                    $frec->dato = trim($obj['Frecuencia']);
                    $frec->id_equipo = $equipo->id_equipo;
                    $frec->save();
                } else if(strcasecmp($equipo->tipo_equipo, "tarjeta_madre")==0){
                    if (!is_numeric($obj['Numero de Slots RAM'])) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un Numero Slots Ram valido para la Tarjeta Madre', 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    }
                    if (!is_numeric($obj['Conexiones para Discos'])) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un numero de Conexiones para discos valida para la Tarjeta Madre', 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    }
                    $ramSop = $this->capacidadAlm($equipo->tipo_equipo, $obj['RAM Soportada']);
                    if (array_key_exists('err', $ramSop)) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $ramSop['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                        DB::rollback();
                        continue;
                    } else {
                        $ramSop = $ramSop['capAlm'];
                    }

                    $num_slots = new DetalleComponente();
                    $num_slots->campo = 'numero_slots';
                    $num_slots->dato = trim($obj['Numero de Slots RAM']);
                    $num_slots->id_equipo = $equipo->id_equipo;
                    $num_slots->save();

                    $ram_soport = new DetalleComponente();
                    $ram_soport->campo = 'ram_soportada';
                    $ram_soport->dato = $ramSop;
                    $ram_soport->id_equipo = $equipo->id_equipo;
                    $ram_soport->save();

                    $disc_conect = new DetalleComponente();
                    $disc_conect->campo = 'conexiones_dd';
                    $disc_conect->dato = trim($obj['Conexiones para Discos']);
                    $disc_conect->id_equipo = $equipo->id_equipo;
                    $disc_conect->save();
                }
                
                DB::commit();
                $respSuccess = array_merge($respSuccess, [['estado' => 'C', 'rowNum' => $obj['rowNum'], 'message' => 'Equipo registrado con exito', 'key'=>strval($obj['rowNum']).'_C']]);
                
            } catch (Exception $e) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error interno. Intentelo mas tarde.', 'key'=>strval($obj['rowNum']).'_E']]);
                DB::rollback();
                continue;
            } catch (QueryException $e) {
                $error_code = $e->errorInfo[1];
                if ($error_code == 1062) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'El código del equipo que ha ingresado ya existe', 'key'=>strval($obj['rowNum']).'_E']]);
                    DB::rollback();
                    continue;
                }
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error interno. Intentelo mas tarde.', 'key'=>strval($obj['rowNum']).'_E']]);
                DB::rollback();
                continue;
            }
            
        }

        return response()->json(['sheetName'=>$request->get('sheetName'), 'success'=>$respSuccess, 'errors'=>$resp, 'encargado_registro'=>$request->get('encargado_registro'), 'fileName'=>$request->get('fileName')], 200);
    }


    public function reg_masivo_correos(Request $request){
        $data = $request->get('data');
        $resp = array();
        $respSuccess = array();
        for ($i = 0; $i < count($data); $i++) {
            $obj = $data[$i];

            $headers = $this->validarHeadersCorreo($obj);
            if($headers!=''){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $headers, 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            $emp = $this->validarEmpAsig($obj['Empleado']);
            if (array_key_exists('err', $emp)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $emp['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $emp = $emp['cedula'];
            }

            $email = $this->validarCorreo($obj['Correo']);
            if(array_key_exists('err', $email)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $email['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }else{
                $email = $email['correo'];
            }

            try{
                $mail= new Correo();
                $mail->correo= $email;
                $mail->contrasena= trim($obj['Pass']);
                $mail->estado= "EU";
                $mail->cedula= $emp;
                $mail->save();  
                $respSuccess = array_merge($respSuccess, [['estado' => 'C', 'rowNum' => $obj['rowNum'], 'message' => 'Correo registrado con exito', 'key'=>strval($obj['rowNum']).'_C']]);    
            }catch(QueryException $e){
                $error_code = $e->errorInfo[1];
                if($error_code == 1062){
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'El correo que ha ingresado ya existe', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Error interno. Intentelo mas tarde.', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } 
        }
        
        return response()->json(['sheetName'=>$request->get('sheetName'), 'success'=>$respSuccess, 'errors'=>$resp, 'encargado_registro'=>$request->get('encargado_registro'), 'fileName'=>$request->get('fileName')], 200);
    }



}
