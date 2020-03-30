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
    Route::get('mostrar_empleados','EmpleadoController@mostrar_todos');

/*API Organización*/
   Route::get('organizaciones', 'OrganizacionController@mostrar_todos');
   Route::get('org_dpto/{punto}', 'DepartamentoController@org_dpto');

/*API Routers*/
    Route::get('listar_routers', 'RouterController@listar_router');
    Route::post('crear_equipo_router', 'RouterController@crear_equipo_router');
    Route::get('marcas_routers', 'RouterController@marcas_routers');
    Route::get('filtrar_routers/{marca}/{fecha_registro?}', 'RouterController@filtrar_routers');
    Route::get('buscar_router/{codigo}', 'RouterController@buscar_router');
    Route::put('eliminar_router/{id}', 'RouterController@eliminar_router');
    Route::post('editar_equipo_router', 'RouterController@editar_equipo_router');
    Route::get('buscar_router_por_id/{id}', 'RouterController@buscar_router_por_id');
    
/* API EQUIPOS */
    Route::post('desktop','EquipoController@crear_Comp_Desktop');
    Route::post('laptop','EquipoController@crear_Comp_laptop');
    Route::get('equipos','EquipoController@getEquipos');
    Route::post('getdesktop','EquipoController@getDesktop');
    Route::get('getEquipoByID/{idequipo}','EquipoController@getEquipoByID');
    Route::post('getDetalleComp','EquipoController@getDetalleComp');
    Route::get('mostrar_codigos','EquipoController@mostrar_codigos');
    
    Route::get('listar_laptops','EquipoController@listar_laptops');


/*API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');


/*API Impresora */
    Route::post('/impresora','ImpresoraController@crear_impresora');
    Route::get('/impresoras','ImpresoraController@mostrar_impresoras');
    Route::get('/impresoras_all','ImpresoraController@mostrar_impresoras_all');
    Route::get('/marcas_impresoras','ImpresoraController@marcas_impresoras');
	Route::get('/impresoras_codigo/{codigo}','ImpresoraController@impresoras_codigo');
    Route::get('filtrar_impresoras/{marca?}/{fecha_asignacion?}', 'ImpresoraController@filtrar_impresoras');
    Route::get('impresoraxequipo','impresoraController@impresoras_equipo');
 /*API Marca*/
    Route::get('listado_marcas', 'MarcaController@listado_marcas');

/**API Ip */
    Route::get('listar_ips', 'IpController@listar_ips');
    Route::get('ips_libres', 'IpController@ips_libres');
    Route::post('crear_equipo_ip', 'IpController@crear_equipo_ip');
    Route::get('filtrar_ip/{direccion_ip}', 'IpController@filtrar_ip');
    Route::get('buscar_ip_por_codigo/{id_ip}', 'IpController@buscar_ip_por_codigo');
    Route::put('ip_asignada/{id_ip}','IpController@ip_asignada');
    Route::post('crear_ip','IpController@crear_ip');






    /*API Tipo Equipo*/
    Route::get('ver_tipos', 'TipoController@mostrar_todos');
    Route::post('crear_tipo', 'TipoController@crear_tipo');