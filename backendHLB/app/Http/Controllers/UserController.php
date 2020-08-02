<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        //print_r("value ",$request->get('usuario'));

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => $credentials, 'token' => $token, 'Creden' => JWTAuth::attempt($credentials) ], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = self::obtener_datos_usurios($credentials['username'])[0];
        return response()->json(['token'=>compact('token')['token'],'user'=>$user]);
    }

    public function obtener_datos_usurios($username){



        return User::select('users.username','users.cedula','empleados.nombre','empleados.apellido','roles.nombre as rol')
        ->join('empleados','empleados.cedula','=','users.cedula')
        ->join('roles','roles.id_rol','=','users.id_rol')
        ->where('users.username','=',$username)
        ->get();

    }

    public function mostrar_usuario_det($username){
        return User::select('users.username','users.cedula','empleados.nombre','empleados.apellido','users.id_rol','empleados.id_departamento','users.created_at')
        ->join('empleados','empleados.cedula','=','users.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('roles','roles.id_rol','=','users.id_rol')
        ->where('users.username','=',$username)
        ->first();
    }


    public function existe_cedula($cedula){
        return $query = User::select('*')
        ->where('users.cedula','=',$cedula)
        //->orWhere('users.numero_serie','=',$numero_serie)
        ->get();
    }


    public function existe_usuario($username){
        return $query = User::select('*')
        ->where('users.username','=',$username)
        //->orWhere('users.numero_serie','=',$numero_serie)
        ->get();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'cedula' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'id_departamento' => 'required',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'id_rol' => 'required',

            //'contrasena', 'id_rol', 'cedula'

            /*
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',*/
        ]);


        $comprobacion_cedula = $this->existe_cedula($request->get('cedula'))->count();
        $comprobacion_usuario = $this->existe_usuario($request->get('username'))->count();




        if ($comprobacion_cedula>0 && $comprobacion_usuario> 0){
            return response()->json(['log' => -1]);
        }else if($comprobacion_cedula>0){
            return response()->json(['log' => -2]);
        }else if($comprobacion_usuario> 0){
            return response()->json(['log' => -3]);
        }

        if($validator->fails()){
            return response()->json(['log' => -4]);
            //return response()->json($validator->errors()->toJson(), 400);
        }

        $empleado = new Empleado();

        $empleado ->cedula=$request->get('cedula');
        $empleado ->nombre=$request->get('nombre');
        $empleado ->apellido=$request->get('apellido');
        $empleado ->id_departamento=$request->get('id_departamento');

        //$impresora ->id_equipo=(int)$request->get('id_equipo');

        $empleado->save();

/*         Empleado::create([
            'cedula' => '222222222222',
            'nombre' => 'Kevin',
            'apellido' => 'Guamanquispe',
            'id_departamento' => '1'
        ]); */

        $user = new User();

        $user ->username=$request->get('username');
        $user ->password=Hash::make($request->get('password'));
        $user ->id_rol=$request->get('id_rol');
        $user ->cedula=$request->get('cedula');

        //$impresora ->id_equipo=(int)$request->get('id_equipo');

        $user->save();

        /* $user = Usuario::create([

            'usuario' => $request->get('usuario'),
            'contrasena' => Hash::make($request->get('contrasena')),
            'id_rol' => $request->get('id_rol'),
            'cedula' => $request->get('cedula'),

            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]); */
        $token = JWTAuth::fromUser($user);
        return response()->json(['log' => 1]);
    }

    public function editar_user_web(Request $request){
        $validator = Validator::make($request->all(), [
            'cedula' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'id_departamento' => 'required',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'id_rol' => 'required',
            'old_cedula'=>'required',
            'old_user'=>'required'
        ]);

        $comprobacion_cedula = $this->existe_cedula($request->get('cedula'))->count();
        $comprobacion_usuario = $this->existe_usuario($request->get('username'))->count();

        if ($comprobacion_cedula > 0 && $comprobacion_usuario > 0 && $request->get('cedula') != $request->get('old_cedula') && $request->get('username') != $request->get('old_user')){
            return response()->json(['log' => 'La cedula y el usuario ingresados ya existen!'], 400);
        }else if($comprobacion_cedula > 0 && $request->get('cedula') != $request->get('old_cedula') ){
            return response()->json(['log' => 'La cedula ingresada ya existe'], 400);
        }else if($comprobacion_usuario > 0 && $request->get('username') != $request->get('old_user')){
            return response()->json(['log' => 'EL usuario ingresado ya existe'], 400);
        }
        if($validator->fails()){
            return response()->json(['log' => 'Los datos enviados estan incompletos o no son correctos'], 400);
        }
        DB::beginTransaction();
        try{
            Empleado::Where("cedula", '=', $request->get('old_cedula'))->update(['cedula' => $request->get('cedula'), 'nombre' => $request->get('nombre'), 'apellido' => $request->get('apellido'), 'id_departamento' => $request->get('id_departamento')]);
            User::Where("username", '=', $request->get('old_user'))->update(['username' => $request->get('username'), 'id_rol' => $request->get('id_rol'), 'password' => $request->get('password')]);
            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['log' => $e], 400); 
        }


    }

    public function registrar_user_web(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cedula' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'id_departamento' => 'required',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'id_rol' => 'required',
        ]);

        $comprobacion_cedula = $this->existe_cedula($request->get('cedula'))->count();
        $comprobacion_usuario = $this->existe_usuario($request->get('username'))->count();

        if ($comprobacion_cedula > 0 && $comprobacion_usuario > 0){
            return response()->json(['log' => 'La cedula y el usuario ingresados ya existen!'], 400);
        }else if($comprobacion_cedula > 0){
            return response()->json(['log' => 'La cedula ingresada ya existe'], 400);
        }else if($comprobacion_usuario > 0){
            return response()->json(['log' => 'EL usuario ingresado ya existe'], 400);
        }
        if($validator->fails()){
            return response()->json(['log' => 'Los datos enviados estan incompletos o no son correctos'], 400);
        }
        DB::beginTransaction();
        try {
            $empleado = new Empleado();
            $empleado ->cedula=$request->get('cedula');
            $empleado ->nombre=$request->get('nombre');
            $empleado ->apellido=$request->get('apellido');
            $empleado ->id_departamento=$request->get('id_departamento');
            $empleado->save();
    
            $user = new User();
            $user ->username=$request->get('username');
            $user ->password=Hash::make($request->get('password'));
            $user ->id_rol=$request->get('id_rol');
            $user ->cedula=$request->get('cedula');
            $user->save();

            $token = JWTAuth::fromUser($user);

            DB::commit();
            return response()->json(['log' => 'exito'], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response()->json(['log' => $e], 400);
        }
    }

    public function get_users(){
        return User::select('users.username','users.cedula','empleados.nombre','empleados.apellido','users.id_rol','empleados.id_departamento','users.created_at','departamentos.nombre as departamento', 'roles.nombre as rol', 'organizaciones.bspi_punto')
        ->join('empleados','empleados.cedula','=','users.cedula')
        ->join('departamentos','departamentos.id_departamento','=','empleados.id_departamento')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->join('roles','roles.id_rol','=','users.id_rol')
        ->get();
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

}
