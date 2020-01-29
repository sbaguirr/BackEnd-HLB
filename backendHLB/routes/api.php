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
    Route::get('mostrar_correos', 'CorreoController@mostrar_correos');
    Route::post('correos', 'CorreoController@crear_correo');
    Route::get('filtrar_correos/{departamento}/{fecha_asignacion?}', 'CorreoController@filtrar_correos');

 /*API Empleado*/
    Route::get('empleados_nombre/{nombreEmpleado}', 'EmpleadoController@buscar_por_nombre');
    Route::get('buscar_empleado/{nombreEmpleado}', 'EmpleadoController@buscar_empleado');


/*API Organización*/
   Route::get('organizaciones', 'OrganizacionController@mostrar_todos');
   Route::get('org_dpto/{punto}', 'DepartamentoController@org_dpto');

/*API Routers*/
   Route::get('listar_routers', 'RouterController@listar_router');
   Route::post('crear_equipo_router', 'RouterController@crear_equipo_router');


/* API EQUIPOS */
    Route::post('desktop','EquipoController@crear_Comp_Desktop');
    Route::post('laptop','EquipoController@crear_Comp_laptop');


/*API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');


/*API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');

/*API Impresora */
    Route::post('/impresora','ImpresoraController@crear_impresora');
    Route::get('/impresoras','ImpresoraController@mostrar_impresoras');
    Route::get('/impresoras_all','ImpresoraController@mostrar_impresoras_all');
    Route::get('/marcas_impresoras','ImpresoraController@marcas_impresoras');

