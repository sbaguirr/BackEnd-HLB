<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Correo;
use App\Models\Ip;
use App\Models\Impresora;
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
        $codigo = (trim(strval($codigo)));
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
            'fuente de poder' => 'fuente_poder', 'fuente poder' => "fuente_poder", 'memoria ram' => 'memoria_ram', 'mouse' => 'Mouse' ,'procesador' => 'procesador', 'regulador' => 'Regulador',
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
            return ['err' => 'Debe ingresar la marca del equipo.'];
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
        if (($tipoEq == 'laptop' || $tipoEq == 'desktop') && !in_array($alm, $listRam)) {
            return ['err' => 'El Tipo RAM Soportada ingresada no es valida. Tipo RAM Soportada: DDR - DDR2 - DDR3 - DDR4.'];
        }
        return ['tipoAlm' => $alm];
    }

    private function capacidadAlm($tipoEq, $alm)
    {
        if (empty($alm)) {
            return ['err' => 'Debe ingresar '.($tipoEq == 'tarjeta_madre' || $tipoEq == 'laptop' ? 'la Ram Soportada':'una capacidad almacenamiento').' para este tipo de equipo.'];
        }
        $alm = strtoupper(trim(strval($alm)));
        $l_alm = explode(' ', $alm);
        $listTA = ['MB', 'GB', 'TB'];
        $listTM = 'GB';
        if (count($l_alm) != 2) {
            return ['err' => 'Debe ingresar una '.($tipoEq == 'tarjeta_madre' || $tipoEq == 'laptop' ? 'Ram Soportada':'capacidad almacenamiento').' valida. Ejemplo: 2 GB'];
        }
        if($tipoEq == 'tarjeta_madre' || $tipoEq == 'laptop' ){
            if (!is_numeric($l_alm[0]) || $l_alm[1] != $listTM) {
                return ['err' => 'Debe ingresar una Ram Soportada valida. Ejemplo: 2 GB'];
            }
        }else{
            if (!is_numeric($l_alm[0]) || !in_array($l_alm[1], $listTA)) {
                return ['err' => 'Debe ingresar una capacidad almacenamiento valida. Ejemplo: 2 GB - 1000 Mb'];
            }
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

    private function validarHeadersImpresoras($obj){
        $headers =  [ 'Empleado', 'Codigo', 'Marca', 'Modelo', 'N/S',
        'Estado','Tipo', 'IP', 'Componente Principal', 'Tinta', 'Cartucho', 'Toner', 'Rodillo', 'Cinta', 'Rollo/Brazalete' , 'Descripcion'];;
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
            
            if (empty($obj['Modelo'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $modelo = trim(strval($obj['Modelo']));

            if (empty($obj['N/S'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $serie = trim(strval($obj['N/S']));
            
            if (empty($obj['Nombre'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $nomb = trim(strval($obj['Nombre']));

            if (empty($obj['Usuario'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $usuario = trim(strval($obj['Usuario']));

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

            if(!empty($obj['Puerta Enlace']) && !filter_var($obj['Puerta Enlace'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'La subred ingresada no es valida. Formato: [0-255].[0-255].[0-255].[0-255]', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $enlace = trim(strval(($obj['Puerta Enlace'])));

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

    private function validarTipoImp($tipo){
        if(empty($tipo)){
            return ['err' => 'Debe ingresar el tipo de impresora a registrar.'];
        }
        $tipo =  strtolower(trim(strval($tipo)));
        $listTipoImp = [ 'multifuncional' => 'Multifuncional', 'matricial'=>'Matricial','brazalete' => 'Brazalete', 'impresora' => 'Impresora', 'escaner' => 'Escáner','escáner' => 'Escáner' ];

        if (array_key_exists($tipo, $listTipoImp)) {
            return ['tipo_imp' => $listTipoImp[$tipo]];
        }
        return ['err' =>'El tipo de impresora ingresado no es valido.'];
    }

    private function get_atributos($list, $obj){
        $count_no_empty = 0;
        $list_atr = array();

        for($i = 0; $i < count($list); $i++){
            if(!empty($obj[$list[$i]])){
                $count_no_empty++;
                $list_atr = array_merge($list_atr, [$list[$i] => trim(strval($obj[$list[$i]]))]);
            }else{
                $list_atr = array_merge($list_atr, [$list[$i] => $obj[$list[$i]]]);
            }
        }
        return ['count_no_empty'=>$count_no_empty, "list_atr"=>$list_atr];
    }

    private function validarAtributosPorTipoImp($tipo, $obj){
        if($tipo == 'Multifuncional'){
            $message_base = 'Las impresoras Multifuncionales deben tener un unico suministro: Tinta, Cartucho o Toner.'.' ';            
            $resp = $this -> get_atributos(['Tinta', 'Cartucho', 'Toner'], $obj);
            $list_mult = $resp['list_atr'];
            $count_no_empty = $resp['count_no_empty'];
            if($count_no_empty == 0){
                return ['err' =>$message_base. 'No se ha ingresado ninguno.'];
            }
            if($count_no_empty > 1){
                return ['err' =>$message_base. 'A ingresado mas de uno.'];

            }
            return ['atributos' => $list_mult];
            
        }
        if($tipo == 'Matricial'){
            $resp = $this -> get_atributos(['Cinta', 'Cartucho'], $obj);
            $list_mult = $resp['list_atr'];
            $count_no_empty = $resp['count_no_empty'];

            if($count_no_empty != 2){
                return ['err' =>"Las impresoras Matriciales deben poseer obligatoriamente Cinta y Cartucho para poder registrase."];
            }

            return ['atributos' => $list_mult];
        }
        if($tipo == 'Brazalete'){
            $resp = $this -> get_atributos(["Toner", 'Rollo/Brazalete', 'Cartucho', "Tinta"], $obj);
            $list_mult = $resp['list_atr'];
            $count_no_empty = $resp['count_no_empty'];

            if($count_no_empty != 4){
                return ['err' =>"Las impresoras de Brazalete deben poseer obligatoriamente Toner, Tinta, Rollo/Brazalete y Cartucho para poder registrase."];
            }

            return ['atributos' => $list_mult];
        }
        if($tipo == 'Impresora'){
            $resp = $this -> get_atributos([ 'Cartucho', "Tinta"], $obj);
            $list_mult = $resp['list_atr'];
            $count_no_empty = $resp['count_no_empty'];

            if($count_no_empty != 2){
                return ['err' =>"Las impresoras deben poseer obligatoriamente Tinta y Cartucho para poder registrase."];
            }

            return ['atributos' => $list_mult];
        } 
        if($tipo == 'Escáner'){
            $resp = $this -> get_atributos(["Rodillo"], $obj);
            $list_mult = $resp['list_atr'];
            $count_no_empty = $resp['count_no_empty'];

            if($count_no_empty != 2){
                return ['err' =>"Las impresoras deben poseer obligatoriamente Tinta y Cartucho para poder registrase."];
            }

            return ['atributos' => $list_mult];
        } 

        return ['err' => 'El tipo de impresora ingresado no es valido.'];

    }
    
    public function reg_masivo_impresoras(Request $request){
        $data = $request->get('data');
        $resp = array();
        $respSuccess = array();

        for ($i = 0; $i < count($data); $i++){
            $obj = $data[$i];

            $headers = $this->validarHeadersImpresoras($obj);
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
            
            if (empty($obj['Modelo'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $modelo = trim(strval($obj['Modelo']));
            
            if (empty($obj['N/S'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $serie = trim(strval($obj['N/S']));

            $tipoImp = $this -> validarTipoImp($obj['Tipo']);
            if (array_key_exists('err', $tipoImp)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $tipoImp['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $tipoImp = $tipoImp['tipo_imp'];
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

            $atributos_tipo = $this->validarAtributosPorTipoImp($tipoImp, $obj);
            if (array_key_exists('err', $atributos_tipo)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $atributos_tipo['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $atributos_tipo = $atributos_tipo['atributos'];
            }

            DB::beginTransaction();

            $impresora = new Impresora();
            $equipo = new Equipo();
            $dt = new \DateTime();
            $dt->format('Y-m-d');

            try{
                $equipo->modelo= $modelo;
                $equipo->fecha_registro=$dt;
                $equipo->codigo=$eq;
                $equipo->tipo_equipo="Impresora";
                $equipo->descripcion = trim(strval($obj['Descripcion']));
                $equipo->asignado = $emp;
                $equipo->encargado_registro = $request->get('encargado_registro');
                $equipo->id_marca = $marca;
                $equipo->numero_serie = $serie;
                $equipo->estado_operativo = $estado;
                $equipo->componente_principal = $comp;
                $equipo->ip = $ip;
                $equipo->save();

                $_id=$equipo->id_equipo;
                $impresora ->tipo=$tipoImp;

                $impresora->toner = array_key_exists("Toner", $atributos_tipo) ? $atributos_tipo['Toner'] : null;
                $impresora->tinta = array_key_exists("Tinta", $atributos_tipo) ? $atributos_tipo['Tinta'] : null;
                $impresora->cartucho = array_key_exists("Cartucho", $atributos_tipo) ? $atributos_tipo['Cartucho'] : null;
                $impresora ->cinta =  array_key_exists("Cinta", $atributos_tipo) ? $atributos_tipo['Cinta'] : null;
                $impresora ->rodillo =  array_key_exists("Rodillo", $atributos_tipo) ? $atributos_tipo['Rodillo'] : null;
                $impresora ->rollo =  array_key_exists('Rollo/Brazalete', $atributos_tipo) ? $atributos_tipo['Rollo/Brazalete'] : null;

                $impresora ->id_equipo=$_id;
                $impresora->save();

                if($ip!==null){
                    $_ip= Ip::find($ip);
                    $_ip->estado= "EU";
                    $_ip->save();
                }
                DB::commit();
                $respSuccess = array_merge($respSuccess, [['estado' => 'C', 'rowNum' => $obj['rowNum'], 'message' => 'Impresora registrada con exito', 'key'=>strval($obj['rowNum']).'_C']]);

            }catch(Exception $e) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' =>'Error interno. Intentelo mas tarde.', 'key'=>strval($obj['rowNum']).'_E']]);
                DB::rollback();
                continue;
            } catch(QueryException $e) {
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

    private function validarTipoEqLD($tipo, $listTipo){
        if (empty($tipo)) {
            return ['err' => 'Debe ingresar el estado del equipo.'];
        }
        $tipo = strtolower(trim(strval($tipo)));
        if (array_key_exists($tipo, $listTipo)) {
            return ['tipo_equipo' => $listTipo[$tipo]];
        }
        return ['err' => 'El tipo de equipo ingresado no es valido para este inventario.'];
    }

    private function validarTipoEqLaptop($tipo){
        $listTipo = [
            'disco duro' => "disco_duro", 'laptop'=>'laptop', 'Laptop' => 'laptop',  'memoria ram' => 'memoria_ram', 
            'procesador' => 'procesador', 'memoriaram' => 'memoria_ram', 'discoduro' => "disco_duro"
        ];
        return $this->validarTipoEqLD($tipo, $listTipo);
    }

    private function validarHeadersLaptops($obj){
        $headers = [ 'Empleado', 'Codigo','Tipo','Principal', 'Marca', 'Modelo', 'N/S',
        'Estado',"NombrePC", "UsuarioPC", "SO",
        "TipoSO", "ServicePack1", "Licencia", 'IP', 'Frecuencia', 'Nucleos', 'RAM Soportada',
        'Slots RAM', 'Capacidad Almacenamiento', 'Tipo Almacenamiento', 'Descripcion'];
        return $this->validarHeaders($headers, $obj);
    }

    
    public function reg_masivo_laptops(Request $request){
        $data = $request->get('data');
        $resp = array();
        $respSuccess = array();
        $laptops = array();
        $componentes = array();
        

        for ($i = 0; $i < count($data); $i++){
            $obj = $data[$i];

            $headers = $this->validarHeadersLaptops($obj);
            if($headers!=''){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $headers, 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }

            $eq = $this->eqByCodigo($obj['Codigo']);
            if (array_key_exists('err', $eq)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $eq['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $eq = $eq['codigo'];
                $obj['Codigo'] = $eq;
            }

            $tipoEq = $this->validarTipoEqLaptop($obj['Tipo']);
            if (array_key_exists('err', $tipoEq)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $tipoEq['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $tipoEq = $tipoEq['tipo_equipo'];
                $obj['Tipo'] = $tipoEq;
            }

            $estado = $this->getEstado($obj['Estado']);
            if (array_key_exists('err', $estado)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $estado['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $estado = $estado['estado'];
                $obj['Estado'] = $estado;
            }

            $marca = $this->getMarcaByNomb($obj['Marca']);
            if (array_key_exists('err', $marca)) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $estado['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            } else {
                $marca = $marca['id_marca'];
                $obj['Marca'] = $marca;
            }
            
            if (empty($obj['Modelo'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un modelo de equipo valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $modelo = trim(strval($obj['Modelo']));
            $obj['Modelo'] = $modelo;
            
            if (empty($obj['N/S'])) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un numero de serie valido', 'key'=>strval($obj['rowNum']).'_E']]);
                continue;
            }
            $serie = trim(strval($obj['N/S']));
            $obj['N/S'] = $serie;

            if($tipoEq == 'laptop'){
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
                $obj['Empleado'] = $emp;

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
                $obj['IP'] = $ip;

                if (empty($obj['SO'])) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un Sistema Operativo para la Laptop', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $so = trim(strval($obj['SO']));
                $obj['SO'] = $so;

                $service =  !is_numeric($obj['ServicePack1']) ? '' : trim(strval($obj['ServicePack1']));
                if ($service != '1' && $service != '0') {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Los valores permitidos para Services Pack 1 son 0 o 1', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $obj['ServicePack1'] = $service;

                $licencia = !is_numeric($obj['Licencia']) ? '' : trim(strval($obj['Licencia']));
                if ($licencia != '1' && $licencia != '0') {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Los valores permitidos para la Licencia son 0 o 1' , 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $obj['Licencia'] = $licencia;

                $tiposo = empty($obj['TipoSO']) ? '' : trim(strval($obj['TipoSO']));
                if ($tiposo != '32 Bits' && $tiposo != '64 Bits') {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Los valores permitidos para la el Tipo de Sistema Operativo son 32 Bits o 64 Bits', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $obj['TipoSO'] = $tiposo;

                if (!is_numeric($obj['Slots RAM'])) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un Numero Slots Ram valido para la Tarjeta Madre', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $obj['Slots RAM'] = intval($obj['Slots RAM']);

                $ramSop = $this->capacidadAlm($tipoEq, $obj['RAM Soportada']);
                if (array_key_exists('err', $ramSop)) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $ramSop['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                } else {
                    $ramSop = $ramSop['capAlm'];
                    $obj['RAM Soportada'] = $ramSop;
                }

                if (empty($obj['NombrePC'])) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un nombre valido para la Laptop.', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $obj['NombrePC'] = trim(strval($obj['NombrePC']));

                if (empty($obj['UsuarioPC'])) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar un usuario valido para la Llaptop.', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                $obj['UsuarioPC'] = trim(strval($obj['UsuarioPC']));

                $tipoAlm = $this->tipoAlm($tipoEq, $obj['Tipo RAM Soportada']);
                if (array_key_exists('err', $tipoAlm)) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $tipoAlm['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                } else {
                    $tipoAlm = $tipoAlm['tipoAlm'];
                    $obj['Tipo RAM Soportada'] = $tipoAlm;
                }

                $laptops = array_merge($laptops, [$obj]);
            }
            else{
                if(empty($obj['Principal'])){
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => 'Debe ingresar el codigo de la laptop a la cual se asignara el componente del registro en el campo "Principal"', 'key'=>strval($obj['rowNum']).'_E']]);
                    continue;
                }
                if($tipoEq == 'procesador'){
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
                }
                if($tipoEq == 'memoria_ram' || $tipoEq == 'disco_duro' ){
                    $tipoAlm = $this->tipoAlm($tipoEq, $obj['Tipo Almacenamiento']);
                    if (array_key_exists('err', $tipoAlm)) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $tipoAlm['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                        continue;
                    } else {
                        $tipoAlm = $tipoAlm['tipoAlm'];
                        $obj['Tipo Almacenamiento'] = $tipoAlm;
                    }
                    $capAlm = $this->capacidadAlm($tipoEq, $obj['Capacidad Almacenamiento']);
                    if (array_key_exists('err', $capAlm)) {
                        $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $obj['rowNum'], 'message' => $capAlm['err'], 'key'=>strval($obj['rowNum']).'_E']]);
                        continue;
                    } else {
                        $capAlm = $capAlm['capAlm'];
                        $obj['Capacidad Almacenamiento'] = $capAlm;
                    }
                }  
                $componentes = array_merge($componentes, [$obj]);
            }  
        }

        // $lap_cop = array();
        // $comp_cop = array();
        for ($l = 0; $l < count($laptops); $l++){
            $k = array();
            $laptop = &$laptops[$l];
            $cod = $laptop['Codigo'];

            $cods_dup = array();
            $cods = [$cod];
            
            $tipoUP = strtoupper(str_replace('_', ' ', $laptop['Tipo'])); 
            $laptop['eq_error'] = false;
            $index_group = '[ '.strval($laptop['rowNum']).' -> '.$tipoUP.' ]';
           
            for ($j = 0; $j < count($componentes); $j++){
                $componente = &$componentes[$j];

                $cod_comp = $componente['Codigo'];
                if(!in_array($cod_comp , $cods)){
                    $cods = array_merge($cods,[$cod_comp]);
                }else{
                    $cods_dup = array_merge($cods_dup,[$cod_comp]);
                }

                if($componente['Principal'] ==  $laptop['Codigo']){
                    $componente['eq_error'] = false;
                    $tipoUP_comp = strtoupper(str_replace('_', ' ', $componente['Tipo'])); 
                    $index_group = $index_group.'--'.'[ '.strval($componente['rowNum']).' -> '.$tipoUP_comp.' ]';
                    $k = array_merge($k,[$componente]);
                }
                
                //$comp_cop = array_merge($comp_cop,[$componente]);
            }

            $laptop['componentes'] = $k;
            $laptop['index_group'] = $index_group;
            $laptop['cods_dup'] = null;
            if(count($cods_dup) != 0){
                $laptop['cods_dup'] = 'Los codigos: '.implode(',',$cods_dup).' se encuentran duplicados.';
            }

           // $lap_cop = array_merge($lap_cop,[$laptop]);
        }

        // $laptops = $lap_cop;
        // $componentes = $comp_cop;


       // return response()->json(['log'=>[$laptops, $componentes]],500);

        for ($i = 0; $i < count($laptops); $i++){
            $laptop = &$laptops[$i];

            if($laptop['cods_dup'] != null){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => $laptop['cods_dup'], 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }            
            
            $comp = $laptop['componentes'];
            $procesador = array_filter($comp, function ($var) { return $var['Tipo'] == 'procesador'; });
            $discos = array_filter($comp, function ($var) { return $var['Tipo'] == 'disco_duro'; });
            $rams = array_filter($comp, function ($var) { return $var['Tipo'] == 'memoria_ram'; });

            if(count($procesador) == 0){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'No se encontro un registro valido correspondiente al procesador para esta laptop.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            if(count($procesador) > 1){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'Solo se puede asignar un registro valido correspondiente al procesador para esta laptop.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            if(count($discos) == 0){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'No se encontro un registro valido correspondiente al disco duro para esta laptop. Se debe ingresar al menos uno.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            if(count($rams) == 0){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'No se encontro un registro valido correspondiente a la memoria ram para esta laptop. Se debe ingresar al menos una.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            if(count($rams) > intval($laptop['Slots RAM'])){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'La cantidad de registros correspondientes a la memoria ram excede el numero de slots que posee esta laptop.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            $ramTotal = 0;
            $TRS_eq = 0;
            $_eq = [];
            foreach($rams as $ram){
                $l_ram = explode(' ', $ram['Capacidad Almacenamiento']);
                $val = $l_ram[1] == 'Mb' ? intval($l_ram[0])/1024 : intval($l_ram[0]);
                $ramTotal += $val;

                if($ram['Tipo Almacenamiento'] == $laptop['Tipo RAM Soportada']){
                    $TRS_eq++;
                }else{
                    $_eq = array_merge($_eq,[$ram['rowNum']]);
                }

            }

            if($TRS_eq != count($rams)){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'Los registros correspondientes a las memorias rams ubicados en: '.implode(',',$_eq). ', poseen un Tipo diferente al soportado por la Laptop.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            if($ramTotal > intval($laptop['RAM Soportada'])){
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' => 'La capacidad total de los registros correspondientes a la memoria ram excede la capacidad de ram soportada por esta laptop.', 'key'=>strval($laptop['rowNum']).'_E']]);
                continue;
            }

            DB::beginTransaction();
            try {
                $computador = new Equipo();
                $computador->codigo = $laptop['Codigo'];
                $computador->fecha_registro = Date('Y-m-d H:i:s');
                $computador->tipo_equipo = $laptop['Tipo'];;
                $computador->id_marca = $laptop['Marca'];;
                $computador->modelo = $laptop['Modelo'];;
                $computador->encargado_registro = $request->get('encargado_registro');
                $computador->estado_operativo = $laptop['Estado'];
                $computador->descripcion = trim(strval($laptop['Descripcion']));
                $computador->numero_serie = $laptop['N/S'];
                $computador->ip = $laptop['IP'];
                $computador->asignado = $laptop['Empleado'];
                $computador->save();

                if($laptop['IP']!==null){
                    $_ip= Ip::find($laptop['IP']);
                    $_ip->estado= "EU";
                    $_ip->save();
                }

                $num_slots = new DetalleComponente();
                $num_slots->campo = 'numero_slots';
                $num_slots->dato = $laptop['Slots RAM'];
                $num_slots->id_equipo = $computador->id_equipo;
                $num_slots->save();

                $ram_soport = new DetalleComponente();
                $ram_soport->campo = 'ram_soportada';
                $ram_soport->dato = $laptop['RAM Soportada'];
                $ram_soport->id_equipo = $computador->id_equipo;
                $ram_soport->save();

                $detEq = new DetalleEquipo();
                $detEq->nombre_pc = $laptop['NombrePC'];
                $detEq->usuario_pc = $laptop['UsuarioPC'];
                $detEq->so = $laptop['SO'];
                $detEq->tipo_so = $laptop['TipoSO'];
                $detEq->services_pack = $laptop['ServicePack1'];
                $detEq->licencia = $laptop['Licencia'];
                $detEq->id_equipo = $computador->id_equipo;
                $detEq->save();

                foreach($comp as $x){

                    $_comp = new Equipo();
                    $_comp->id_marca = $x['Marca'];
                    $_comp->codigo = $x['Codigo'];
                    $_comp->modelo = $x['Modelo'];
                    $_comp->numero_serie = $x['N/S'];
                    $_comp->descripcion = trim(strval($x['Descripcion']));
                    $_comp->encargado_registro = $request->get('encargado_registro');
                    $_comp->fecha_registro = Date('Y-m-d H:i:s');
                    $_comp->estado_operativo = $x['Estado'];
                    $_comp->asignado = $laptop['Empleado'];
                    $_comp->componente_principal = $computador->id_equipo;
                    $_comp->tipo_equipo = $x['Tipo'];
                    $_comp->save();

                    if (
                        strcasecmp($_comp->tipo_equipo, "memoria_ram") == 0 ||
                        strcasecmp($_comp->tipo_equipo, "disco_duro") == 0
                    ) {
                        
                        $tipo = new DetalleComponente();
                        $tipo->campo = 'tipo';
                        $tipo->dato = $x['Tipo Almacenamiento'];
                        $tipo->id_equipo = $_comp->id_equipo;
                        $tipo->save();
    
                        $capacidad = new DetalleComponente();
                        $capacidad->campo = 'capacidad';
                        $capacidad->dato = $x['Capacidad Almacenamiento'];
                        $capacidad->id_equipo = $_comp->id_equipo;
                        $capacidad->save();
                    } else if (strcasecmp($_comp->tipo_equipo, "procesador") == 0) {
                        
                        $nucleos = new DetalleComponente();
                        $nucleos->campo = 'nucleos';
                        $nucleos->dato = intval($x['Nucleos']);
                        $nucleos->id_equipo = $_comp->id_equipo;
                        $nucleos->save();
    
                        $frec = new DetalleComponente();
                        $frec->campo = 'frecuencia';
                        $frec->dato = floatval($x['Frecuencia']);
                        $frec->id_equipo = $_comp->id_equipo;
                        $frec->save();
                    }
                }
                DB::commit();
                $respSuccess = array_merge($respSuccess, [['estado' => 'C', 'rowNum' => $laptop['index_group'], 'message' => 'Laptop registrada con exito', 'key'=>strval($laptop['rowNum']).'_C']]);

            } catch (Exception $e) {
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['index_group'], 'message' =>'Error interno. Intentelo mas tarde.', 'key'=>strval($laptop['rowNum']).'_E']]);
                DB::rollback();
                continue;
            } catch (QueryException $e) {
                $error_code = $e->errorInfo[1];
                if ($error_code == 1062) {
                    $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['rowNum'], 'message' =>'El código de la laptop que ha ingresado ya existe', 'key'=>strval($laptop['rowNum']).'_E']]);
                    DB::rollback();
                    continue;
                }
                $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $laptop['rowNum'], 'message' =>'Error Interno ('. strval($e->errorInfo[1]).'): '.strval($e->errorInfo[2]), 'key'=>strval($laptop['rowNum']).'_E']]);
                DB::rollback();
                continue;
            }

        }

        $comp_err = array_filter($componentes, function ($var) { return $var['eq_error']; });

        foreach($comp_err as $err){
            $resp = array_merge($resp, [['estado' => 'E', 'rowNum' => $err['rowNum'], 'message' =>'No se encontro registro valido perteneciente a una Laptop al cual se puediera agregar este componente.', 'key'=>strval($err['rowNum']).'_E']]);
        }

        return response()->json(['log'=>[$laptops,$componentes],'sheetName'=>$request->get('sheetName'), 'success'=>$respSuccess, 'errors'=>$resp, 'encargado_registro'=>$request->get('encargado_registro'), 'fileName'=>$request->get('fileName')], 200);

    }
    

}
