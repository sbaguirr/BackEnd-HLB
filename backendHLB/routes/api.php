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
    Route::get('marcas_routers', 'RouterController@marcas_routers');
    Route::get('filtrar_routers/{marca}/{fecha_registro?}', 'RouterController@filtrar_routers');
    Route::get('buscar_router/{codigo}', 'RouterController@buscar_router');

/* API EQUIPOS */
    Route::post('desktop','EquipoController@crear_Comp_Desktop');
    Route::post('laptop','EquipoController@crear_Comp_laptop');
    Route::put('deleteequipo/{idequipo}','EquipoController@deleteEquipoByID');
    Route::post('getequipos','EquipoController@getEquipos');
    Route::get('getDesktopByID/{idequipo}','EquipoController@getDesktopByID');
    Route::get('getLaptopByID/{idequipo}','EquipoController@getLaptopByID');
    Route::post('getDetalleComp','EquipoController@getDetalleComp');
    Route::put("editlaptop/{idequipo}","EquipoController@editLaptop");
    Route::put("editdesktop/{idequipo}","EquipoController@editDesktop");


/*API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');


/*API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');

/*API Impresora */
    Route::post('/impresora','ImpresoraController@crear_impresora');
    Route::get('/impresoras','ImpresoraController@mostrar_impresoras');
    Route::get('/impresoras_all','ImpresoraController@mostrar_impresoras_all');
    Route::get('/marcas_impresoras','ImpresoraController@marcas_impresoras');
	Route::get('/impresoras_codigo/{codigo}','ImpresoraController@impresoras_codigo');
    Route::get('filtrar_impresoras/{marca?}/{fecha_asignacion?}', 'ImpresoraController@filtrar_impresoras');

 /*API Marca*/
    Route::get('listado_marcas', 'MarcaController@listado_marcas');

/**API Ip */
    Route::get('listar_ips', 'IpController@listar_ips');
    Route::post('crear_equipo_ip', 'IpController@crear_equipo_ip');
    Route::get('filtrar_ip/{direccion_ip}', 'IpController@filtrar_ip');
