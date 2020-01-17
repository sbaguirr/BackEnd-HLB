<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*API Correo*/
    Route::post('correos', 'CorreoController@crear_correo');
    Route::get('correos/{fecha_asignacion}','CorreoController@buscar_por_fecha');

 /*API Empleado*/   
    Route::get('empleados_nombre/{nombreEmpleado}', 'EmpleadoController@buscar_por_nombre');
    Route::get('empleados_punto/{punto}', 'EmpleadoController@buscar_por_punto');
    Route::get('empleados_estado/{estado}', 'EmpleadoController@buscar_por_estado');
    Route::get('empleados_dpto/{departamento}', 'EmpleadoController@buscar_por_departamento');

/* API EQUIPOS */
   Route::post('desktop','EquipoController@crear_Comp_Desktop');