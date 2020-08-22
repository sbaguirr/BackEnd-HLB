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
/*-------------APP ROUTES-------------*/

    Route::post('/upload/images', 'ImageUploadController@uploadImages');
    Route::get('/path_all','ImageUploadController@obtener_rutas');

    Route::post('register', 'UserController@register');
    Route::post('registrar_user_web', 'UserController@registrar_user_web');
    Route::post('login', 'UserController@login');
    Route::get('user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');
    Route::get('/obtener_datos_usurios/{username}', 'UserController@obtener_datos_usurios');
    Route::get('/mostrar_usuario_det/{username}', 'UserController@mostrar_usuario_det');
    Route::get('/get_users', 'UserController@get_users');
    Route::put('/editar_user_web', 'UserController@editar_user_web');




/*API Correo*/
    Route::get('mostrar_correos', 'CorreoController@mostrar_correos');
    Route::post('correos', 'CorreoController@crear_correo');
    Route::post('filtrar_correos', 'CorreoController@filtrar_correos');
    Route::get('correo_id/{correo_id}', 'CorreoController@correo_id');
    Route::put('editar_correo', 'CorreoController@editar_correo');
    Route::put('eliminar_correo/{id_correo}', 'CorreoController@eliminar_correo');

/*API Empleado*/
    Route::get('buscar_empleado/{nombreEmpleado}', 'EmpleadoController@buscar_empleado');
    Route::get('empleados_sistemas', 'EmpleadoController@empleados_sistemas');

/*API Marcas */
    Route::post('filtrar_marcas', 'MarcaController@filtrar_marcas');
    Route::get('marca_id/{marca_id}', 'MarcaController@marca_id');
    Route::delete('eliminar_marca/{id_marca}', 'MarcaController@eliminar_marca');

/*API Organización*/
   Route::get('organizaciones', 'OrganizacionController@mostrar_todos');
   Route::get('org_dpto/{punto}', 'DepartamentoController@org_dpto');
   Route::get('mostrar_departamentos','DepartamentoController@mostrar_departamentos');
   Route::get('mostrar_roles','DepartamentoController@mostrar_roles');
   Route::get('mostrar_dep_org','DepartamentoController@org_dpto_all');

/*API Routers*/
    Route::get('listar_routers', 'RouterController@listar_router');
    Route::post('crear_equipo_router', 'RouterController@crear_equipo_router');
    Route::get('marcas_routers', 'RouterController@marcas_routers');
    Route::post('filtrar_routers', 'RouterController@filtrar_routers');
    Route::get('buscar_router/{codigo}', 'RouterController@buscar_router');
    Route::put('eliminar_router/{id}', 'RouterController@eliminar_router');
    Route::post('editar_equipo_router', 'RouterController@editar_equipo_router');
    Route::get('buscar_router_por_id/{id}', 'RouterController@buscar_router_por_id');

/* API EQUIPOS */
    Route::post('desktop','EquipoController@crear_Comp_Desktop');
    Route::post('laptop','EquipoController@crear_Comp_laptop');
    Route::put('deleteequipo/{idequipo}/{tipo}','EquipoController@deleteEquipoByID');
    Route::post('getequipos','EquipoController@getEquipos');
    Route::get('getDesktopByID/{idequipo}','EquipoController@getDesktopByID');
    Route::get('getLaptopByID/{idequipo}','EquipoController@getLaptopByID');
    Route::put("editlaptop/{idequipo}","EquipoController@editLaptop");
    Route::put("editdesktop/{idequipo}","EquipoController@editDesktop");
    Route::get('mostrar_equipos_paginado/{size}','EquipoController@mostrar_equipos_paginado');
    Route::get('/equipo_codigo_paginado/{codigo}/{size}','EquipoController@equipo_codigo_paginado');
    Route::get('/equipos_codigo/{codigo}','EquipoController@equipos_codigo');

    Route::get('filtrar_equipos_paginado/{marca?}/{fecha_asignacion?}/{estado?}/{size}', 'EquipoController@filtrar_equipos_paginado');
    Route::put('eliminar_otros_equipos/{id}','EquipoController@eliminar_otros_equipos');
    Route::get('/obtener_otro_equipo_por_id/{id_otro_equipo}','EquipoController@obtener_otro_equipo_por_id');


/*API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');


/*API Impresora */
    Route::post('/impresora','ImpresoraController@crear_impresora');
    Route::get('/impresoras','ImpresoraController@mostrar_impresoras');
    Route::get('/impresoras_all','ImpresoraController@mostrar_impresoras_all');
    Route::get('/marcas_impresoras','ImpresoraController@marcas_impresoras');
    Route::post('/impresora_nueva','ImpresoraController@crear_impresora_nueva');
	Route::get('/impresoras_codigo/{codigo}','ImpresoraController@impresoras_codigo');
    Route::get('filtrar_impresoras/{marca?}/{fecha_asignacion?}', 'ImpresoraController@filtrar_impresoras');
    Route::put('eliminar_impresora/{id}','ImpresoraController@eliminar_impresora');
	Route::get('/impresoras_codigo_paginado/{codigo}/{size}','ImpresoraController@impresoras_codigo_paginado');
    Route::get('filtrar_impresoras_paginado/{marca?}/{fecha_asignacion?}/{estado?}/{size}', 'ImpresoraController@filtrar_impresoras_paginado');
    Route::get('/mostrar_impresoras_codigo_paginado','ImpresoraController@mostrar_impresoras_codigo_paginado');
    Route::get('/impresoras_paginado/{size}','ImpresoraController@mostrar_impresoras_paginado');
    Route::get('/obtener_impresora_por_id/{id_impresora}','ImpresoraController@obtener_impresora_por_id');


/*API Ip */
    Route::get('listar_ips', 'IpController@listar_ips');
    Route::get('mostrar_ips', 'IpController@mostrar_ips');
    Route::get('mostrar_ips_detalladas', 'IpController@mostrar_ips_detalladas');
    Route::get('listar_ips_prueba', 'IpController@listar_ips_prueba');
    Route::get('ips_libres', 'IpController@ips_libres');
    Route::post('crear_equipo_ip', 'IpController@crear_equipo_ip');
    Route::post('crear_ip', 'IpController@crear_ip');
    Route::post('editar_ip', 'IpController@editar_ip');
    Route::get('filtrar_ip/{direccion_ip}', 'IpController@filtrar_ip');
    Route::get('buscar_ip_por_codigo/{id_ip}', 'IpController@buscar_ip_por_codigo');
    Route::put('ip_asignada/{id_ip}','IpController@ip_asignada');
    Route::get('ipbyidonly/{id}', 'IpController@Ip_ID_Only');
    Route::delete('eliminar_ip/{id_ip}', 'IpController@eliminar_ip');


/*API Solicitudes */
    Route::post('filtrar_solicitudes', 'SolicitudController@filtrar_solicitudes');
    Route::get('contar_solicitudes', 'SolicitudController@contar_solicitudes');
    Route::get('info_solicitud_id/{id}', 'SolicitudController@info_solicitud_id');
    Route::get('info_atencion_solicitud_id/{id}', 'AtencionSolicitudController@info_atencion_solicitud_id');
    Route::put('cambiar_estado_solicitud/{id}/{estado}', 'SolicitudController@cambiar_estado_solicitud');
    Route::post('crear_atencion_solicitud', 'AtencionSolicitudController@crear_atencion_solicitud');
    


/*API para notificaciones móviles */
    Route::put('actualizar_token', 'UserController@actualizar_token');

/*API para mantenimiento */
    Route::get('solicitudes_en_progreso', 'SolicitudController@solicitudes_en_progreso');
    Route::post('crear_mantenimiento', 'MantenimientoController@crear_mantenimiento');
    Route::post('mostrar_mantenimientos', 'MantenimientoController@mostrar_mantenimientos');
    Route::put('editar_mantenimiento', 'MantenimientoController@editar_mantenimiento');
    Route::get('mantenimiento_id/{id}', 'MantenimientoController@mantenimiento_id');
    Route::post('equipos_por_codigo', 'MantenimientoController@equipos_por_codigo');
    Route::get('obtener_tokens', 'SolicitudController@obtener_tokens');
    Route::delete('eliminar_mantenimiento/{id_mantenimiento}', 'MantenimientoController@eliminar_mantenimiento');




/*-------------WEB ROUTES-------------*/

/* API Empleados */
    Route::get('mostrar_empleados','EmpleadoController@mostrar_todos');
    Route::get('impresoraxequipo','impresoraController@impresoras_equipo');


/* API DEPARTAMENTO*/
    Route::get('departamentos','DepartamentoController@mostrar_todos');


/* API Marca*/
    Route::get('listado_marcas', 'MarcaController@listado_marcas');
    Route::post('crear_marca','MarcaController@crear_marca');
    Route::put('editar_marca','MarcaController@editar_marca');

/* API Equipo */
    Route::get('mostrar_codigos','EquipoController@mostrar_codigos');
    Route::post('otro_equipo','EquipoController@crear_otro_equipo');
    Route::get('tipo_equipo','EquipoController@mostrar_tipo_equipo');
    Route::get('mostrar_equipos','EquipoController@mostrar_equipos');
    Route::put('editar_equipo','EquipoController@editar_equipo');
    Route::get('obtenerInfoLaptop/{idequipo}','EquipoController@obtenerInfoLaptop');
    Route::get('listar_laptops','EquipoController@listar_laptops');
    Route::get('obtenerInfoDesktop/{idequipo}','EquipoController@obtenerInfoDesktop');
    Route::get('listar_desktops','EquipoController@listar_desktops');
    Route::get('equipo_id/{id_equipo}','EquipoController@equipo_id');
    Route::put('eliminar_equipo/{id_equipo}','EquipoController@eliminar_equipo');
    Route::get('reporte-general','EquipoController@reporte_general');
    Route::get('reporte-bajas','EquipoController@reporte_bajas');
    Route::put('darDeBajaEquipoID/{idequipo}/{tipo}','EquipoController@darDeBajaEquipoID');
    Route::post('crear_laptop','EquipoController@crear_laptop');
    Route::post('editar_laptop','EquipoController@editar_laptop');
    Route::post('crear_desktop','EquipoController@crear_desktop');
    Route::post('editar_desktop','EquipoController@editar_desktop');
    Route::get('resumen-bajas','EquipoController@resumen_bajas');
    Route::get('info_extra/{id_equipo}','EquipoController@info_extra');
    Route::get('listado_codigos','EquipoController@listado_codigos');


/* API DetalleEquipo */
    Route::get('listar_so','DetalleEquipoController@listar_so');
    Route::get('listar_office','DetalleEquipoController@listar_office');

/* API IP */
    Route::post('crear_ip','IpController@crear_ip');
    Route::put('editar_ip','IpController@editar_ip');
    Route::get('es_ip_enuso/{ip}','IpController@es_ip_enuso');
    Route::get('ip_id/{id_ip}','IpController@ip_id');
    Route::delete('eliminar_ip/{id_ip}', 'IpController@eliminar_ip');



/*  API Impresora*/
    Route::get('impresora_equipo','ImpresoraController@impresoras_equipo');
    Route::put('editar_impresora','ImpresoraController@editar_impresora');
    Route::get('/impresora_id/{id_equipo}','ImpresoraController@impresora_id');


/*API Routers*/
    Route::get('router_id/{id_equipo}', 'RouterController@router_id');
    Route::post('editar_router', 'RouterController@editar_router');

/* API PROGRAMAS */
    Route::get('programas', 'ProgramaInstaladoController@programas');
    Route::get('buscar_programa/{nombre}', 'ProgramaInstaladoController@buscar_programa');
    Route::post('filtrar_programas', 'ProgramaInstaladoController@filtrar_programas');
    Route::get('editores_programa', 'ProgramaInstaladoController@editores_programa');
    Route::post('crear_programa', 'ProgramaInstaladoController@crear_programa');
    Route::put('eliminar_programa/{id}', 'ProgramaInstaladoController@eliminar_programa');
    Route::post('editar_programa', 'ProgramaInstaladoController@editar_programa');
    Route::post('lista_programas_id', 'ProgramaInstaladoController@lista_programas_id');
    Route::get('buscar_programa_id/{id_programa}', 'ProgramaInstaladoController@buscar_programa_id');

/* API SOLICITUDES */
    Route::post('crear_solicitud', 'SolicitudController@crear_solicitud');
    Route::get('mostrar_solicitudes', 'SolicitudController@mostrar_solicitudes');
    Route::get('mostrar_solicitudes/{id_user}', 'SolicitudController@mostrar_solicitudes_user');

/* API IMPORT */
    Route::post('masivo_equipos','ImportController@reg_masivo_equipos');
    Route::post('masivo_dirips','ImportController@reg_masivo_dirips');
    Route::post('masivo_correos','ImportController@reg_masivo_correos');
    Route::post('masivo_routers','ImportController@reg_masivo_routers');
    Route::post('masivo_impresoras','ImportController@reg_masivo_impresoras');
    

