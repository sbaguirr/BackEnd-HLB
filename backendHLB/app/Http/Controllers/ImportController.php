<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Correo;
use App\Models\Ip;
use App\Models\Router;
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
        $cedula = trim(strval($cedula));
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
        $cedula = trim(strval($cedula));
        return $this->empByCedula($cedula);
    }

    private function eqByCodigo($codigo)
    {
        if (empty($codigo)) {
            return ['err' => 'Debe ingresar el codigo para registrar el equipo.'];
        }
        $codigo = strtoupper(trim(strval($codigo)));
        $result = Equipo::Where('codigo', '=', $codigo)->get();
        if (count($result) > 0) {
            return ['err' => 'Ya existe un equipo registrado con ese codigo.'];
        }
        return ['codigo' => $codigo];
    }

    private function compPrinByCod($comp)
    {
        $codigo = strtoupper(trim(strval($comp)));
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
        $estado = strtolower(trim(strval($estado)));
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
        $tipo = strtolower(trim(strval($tipo)));
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
        $marca = trim(strval($marca));
        $result = Marca::select('id_marca')->where('nombre', '=', strtolower(trim($marca)))->get();
        if (count($result)==0) {
            return ['err' => 'La marca ingresa no existe. Debe registrarla.'];
        }
        return ['id_marca' => ($result[0]->id_marca)];
    }

    private function getIPByDir($ip)
    {
        $ip = trim(strval($ip));
        $result = IP::Where('direccion_ip', '=', $ip)->get();
        if (count($result) == 0) {
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
        $alm = strtoupper(trim(strval($alm)));
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
        $alm = strtoupper(trim(strval($alm)));
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
        $headers = ['Empleado', 'Codigo', 'Marca', 'Modelo', 'N/S',
        'Estado', 'Tipo', 'IP', 'Capacidad Almacenamiento', 'Tipo Almacenamiento', 'Numero de Slots RAM', 'RAM Soportada',
        'Conexiones para Discos','Nucleos', 'Frecuencia', 'Componente Principal', 'Descripcion'];
        return $this->validarHeaders($headers, $obj);
    }

    private function validarHeadersCorreo($obj){
        $headers = ['Empleado', 'Correo', 'Pass'];
        return $this->validarHeaders($headers, $obj);
    }

    private function validarHeadersIPs($obj){
        $headers =  ['IP', 'Hostname', 'Subred', 'Fortigate', 'Maquinas Adicionales', 'Observacion'];
        return $this->validarHeaders($headers, $obj);
    }

    private function validarHeadersRouters($obj){
        $headers =  ['Empleado', 'Codigo', 'Marca', 'Modelo', 'N/S',
        'Estado','Nombre', 'Pass', 'Usuario', 'Clave', 'IP', 'Puerta Enlace', 'Descripcion'];
        return $this->validarHeaders($headers, $obj);
    }

    private function validarCorreo($correo){
        
        if(empty($correo)){
            return ['err'=>'Debe ingresar un correo para el registro.'];
        }
        if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
            return ['err' => 'El correo ingresado no es valido.'];
        }
        $correo = trim(strval($correo));
        $emails = Correo::Where('correo','=',$correo)->get();
        if(count($emails) > 0){
            return ['err' => 'El correo ingresado ya existe. Ingrese uno Nuevo.'];
        }
        return ['correo' => $correo];
    }

    private function validarPassWord($pass){
        if(empty($pass)){
            return ['err'=>'Debe ingresar una contraseña valida.'];
        }
        $pass = trim(strval($pass));
        $exp_reg = '/^(?=.*[1-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{5,10}$/';
        if(!preg_match($exp_reg, $pass)){
            return ['err' => 'La contraseña debe tener de 5 a 10 caracteres e incluir mayúsculas, minúsculas y números'];
        }
        return ['pass' => $pass];

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

            if (empty(trim(strval($obj['Modelo'])))) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            if (empty(trim(strval($obj['N/S'])))) {
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
                    $comp = $comp['comp'];
                }
            }

            DB::beginTransaction();
            try {
                $equipo = new Equipo();
                $dt = new \DateTime();
                $dt->format('Y-m-d');
                $equipo->codigo = $eq;
                $equipo->modelo = trim(strval($obj['Modelo']));
                $equipo->fecha_registro = $dt;
                $equipo->descripcion = trim(strval($obj['Descripcion']));
                $equipo->id_marca = $marca;
                $equipo->asignado = $emp;
                $equipo->tipo_equipo = $tipoEq;
                $equipo->numero_serie =  trim(strval($obj['N/S']));
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
                    $nucleos->dato = intval($obj['Nucleos']);
                    $nucleos->id_equipo = $equipo->id_equipo;
                    $nucleos->save();

                    $frec = new DetalleComponente();
                    $frec->campo = 'frecuencia';
                    $frec->dato = floatval($obj['Frecuencia']);
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
                    $num_slots->dato = intval($obj['Numero de Slots RAM']);
                    $num_slots->id_equipo = $equipo->id_equipo;
                    $num_slots->save();

                    $ram_soport = new DetalleComponente();
                    $ram_soport->campo = 'ram_soportada';
                    $ram_soport->dato = $ramSop;
                    $ram_soport->id_equipo = $equipo->id_equipo;
                    $ram_soport->save();

                    $disc_conect = new DetalleComponente();
                    $disc_conect->campo = 'conexiones_dd';
                    $disc_conect->dato = intval($obj['Conexiones para Discos']);
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
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error Interno ('. strval($e->errorInfo[1]).'): '.strval($e->errorInfo[2]), 'key'=>strval($obj['rowNum']).'_E']]);
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

            $password = $this->validarPassWord($obj['Pass']);
            if(array_key_exists('err', $password)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $password['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }else{
                $password = $password['pass'];
            }

            try{
                $mail= new Correo();
                $mail->correo= $email;
                $mail->contrasena= $password;
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
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error Interno ('. strval($e->errorInfo[1]).'): '.strval($e->errorInfo[2]), 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } 
        }
        
        return response()->json(['sheetName'=>$request->get('sheetName'), 'success'=>$respSuccess, 'errors'=>$resp, 'encargado_registro'=>$request->get('encargado_registro'), 'fileName'=>$request->get('fileName')], 200);
    }

    private function validarIPNueva($ip){

        if (empty($ip)) {
            return ['err' => 'Debe ingresar una IP valida.'];
        }
        $ip = trim(strval($ip));
        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            return ['err' => 'La direccion IP no es valida. Formato: [0-255].[0-255].[0-255].[0-255]'];
        }
        $result = IP::Where('direccion_ip', '=', $ip)->get();
        if(count($result) > 0){
            return ['err' => 'La direccion IP ya esta registrada.'];
        }
        return ['ip' => $ip];
    }

    private function validarHostName($host){
        if(empty($host)){
            return ['err' => 'Debe ingresar un hostname valido.'];
        }
        $host = trim(strval($host));
        return ['hostname' => $host];
    }

    private function validarFortigate($ftg){
        if(empty($ftg)){
            return ['err' => 'Debe ingresar un Fortigate valido.'];
        }
        $ftg = trim(strval($ftg));
        return ['fortigate' => $ftg];
    } 

    private function validarSubred($subred){
        if(empty($subred)){
            return ['err' => 'Debe ingresar un Fortigate valido.'];
        }
        $subred = trim(strval(($subred)));
        if(!filter_var($subred, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            return ['err' => 'La subred ingresada no es valida. Formato: [0-255].[0-255].[0-255].[0-255]'];
        }
        return ['subred' => $subred];
    }

    
    public function reg_masivo_dirips(Request $request){
        $data = $request->get('data');
        $resp = array();
        $respSuccess = array();
        for ($i = 0; $i < count($data); $i++){
            $obj = $data[$i];

            $headers = $this->validarHeadersIPs($obj);
            if($headers!=''){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $headers, 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            $ip = $this->validarIPNueva($obj['IP']);
            if(array_key_exists('err', $ip)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $ip['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            else{
                $ip = $ip['ip'];
            }

            $subred = $this->validarSubred($obj['Subred']);
            if(array_key_exists('err', $subred)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $subred['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }else{
                $subred = $subred['subred'];
            }

            $ftg = $this->validarFortigate($obj['Fortigate']);
            if(array_key_exists('err', $ftg)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $ftg['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            else{
                $ftg = $ftg['fortigate'];
            }

            $hostname = $this->validarHostName($obj['Hostname']);
            if(array_key_exists('err',$hostname)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $hostname['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }else{
                $hostname = $hostname['hostname']; 
            }

            if(!empty($obj['Maquinas Adicionales']) && !is_numeric($obj['Maquinas Adicionales'])){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Ingrese un valor valido para las maquinas adicionales.', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            DB::beginTransaction();
            try {
                $dirip = new Ip();
                $dirip->direccion_ip = $ip;
                $dirip->hostname = $hostname;
                $dirip->subred = $subred;
                $dirip->estado = "L";
                $dirip->fortigate = $ftg;
                $dirip->observacion = trim(strval($obj['Observacion']));
                $dirip->maquinas_adicionales = empty($obj['Maquinas Adicionales']) ? 0 : intval($obj['Maquinas Adicionales']);
                $dirip->encargado_registro = $request->get('encargado_registro');
                $dirip->save();
                DB::commit();
                $respSuccess = array_merge($respSuccess, [['estado' => 'C', 'rowNum' => $obj['rowNum'], 'message' => 'IP registrada con exito', 'key'=>strval($obj['rowNum']).'_C']]);    
                
            } catch (QueryException $e) {
                DB::rollback();
                $error_code = $e->errorInfo[1];
                if($error_code == 1062){
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'La direccion IP que ha ingresado ya existe. Por favor ingrese una direccion IP que no exista.', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error Interno ('. strval($e->errorInfo[1]).'): '.strval($e->errorInfo[2]), 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

        }

        return response()->json(['sheetName'=>$request->get('sheetName'), 'success'=>$respSuccess, 'errors'=>$resp, 'encargado_registro'=>$request->get('encargado_registro'), 'fileName'=>$request->get('fileName')], 200);
    }


    public function reg_masivo_routers(Request $request){
        $data = $request->get('data');
        $resp = array();
        $respSuccess = array();

        for ($i = 0; $i < count($data); $i++){
            $obj = $data[$i];
        
            $headers = $this->validarHeadersRouters($obj);
            if($headers!=''){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $headers, 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
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
            $modelo = trim(strval($obj['Modelo']));
            if (empty($modelo)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $serie = trim(strval($obj['N/S']));
            if (empty($serie)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $nomb = trim(strval($obj['Nombre']));
            if (empty($nomb)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $usuario = trim(strval($obj['Usuario']));
            if (empty($usuario)) {
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

            $password = $this->validarPassWord($obj['Pass']);
            if(array_key_exists('err', $password)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $password['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }else{
                $password = $password['pass'];
            }

            $clave = $this->validarPassWord($obj['Clave']);
            if(array_key_exists('err', $clave)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $clave['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }else{
                $clave = $clave['pass'];
            }

            $enlace = trim(strval(($obj['Puerta Enlace'])));
            if(!empty($enlace) && !filter_var($enlace, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'La subred ingresada no es valida. Formato: [0-255].[0-255].[0-255].[0-255]', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            DB::beginTransaction();
            try{
                $equipo = new Equipo();
                $router = new Router();   
        
                $equipo->fecha_registro = Date('Y-m-d H:i:s');
                $equipo->estado_operativo = $estado;
                $equipo->codigo = $eq;
                $equipo->tipo_equipo = "Router";
                $equipo->id_marca = $marca;
                $equipo->modelo = $modelo;
                $equipo->numero_serie = $serie;
                $equipo->descripcion = trim(strval($obj['Descripcion']));
                $equipo->asignado = $emp;
                $equipo->encargado_registro = $request->get('encargado_registro');
                $equipo->componente_principal = null;
                $equipo->ip = $ip;
                $equipo->save(); 
        
                $id_equip = $equipo->id_equipo;
                $router->id_equipo = $id_equip;
                $router->nombre = $nomb;
                $router->pass = $password;
                $router->puerta_enlace = $enlace;
                $router->usuario = $usuario;
                $router->clave = $clave;

                if($ip!==null){
                    $_ip= Ip::find($ip);
                    $_ip->estado= "EU";
                    $_ip->save();
                }   
                $router->save();
                DB::commit();
                $respSuccess = array_merge($respSuccess, [['estado' => 'C', 'rowNum' => $obj['rowNum'], 'message' => 'Router registrado con exito', 'key'=>strval($obj['rowNum']).'_C']]);       
            }catch(QueryException $e){
                DB::rollback();
                $error_code = $e->errorInfo[1];
                if($error_code == 1062){
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'El codigo ingresado ya existe. Por favor ingrese uno que no exista.', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error Interno ('. strval($e->errorInfo[1]).'): '.strval($e->errorInfo[2]), 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }    
        }
        return response()->json(['sheetName'=>$request->get('sheetName'), 'success'=>$respSuccess, 'errors'=>$resp, 'encargado_registro'=>$request->get('encargado_registro'), 'fileName'=>$request->get('fileName')], 200);
    }


}
