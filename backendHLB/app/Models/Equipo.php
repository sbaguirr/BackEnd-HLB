<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';
    protected $primaryKey = 'id_equipo';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_registro','estado_operativo','codigo','tipo_equipo', 'modelo', 'numero_serie', 'descripcion', 'id_marca',
        'encargado_registro', 'componente_principal', 'ip', 'asignado', 'id_estado_equipo', 'id_router'
      
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // RECURSIVIDAD
    // Relación: Equipo - Componentes (1 - M)
    public function parent()
    {
        return $this->hasMany('App\Models\Equipo', 'componente_principal');
    }
    // Relación: Equipo - Componentes (1 - M)
    public function equipos()
    {
        return $this->hasMany('App\Models\Equipo', 'componente_principal')->with('equipos');
    }
    

    // Relación: Equipo - ProgramaEquipo (1 - M)
    public function programa_equipos()
    {
        return $this->hasMany('App\Models\ProgramaEquipo', 'id_equipo');
    }


    // Relación: Usuario - Equipo (1 - M)
     public function usuarios()
    {
        return $this->belongsTo('App\Models\Usuario', 'encargado_registro');
    }

    public function asignado()
    {
        return $this->belongsTo('App\Models\Empleado', 'asignado');
    }

    // Relación: Equipo - Router (1 - 0/1)
    public function routers()
    {
        return $this->hasOne('App\Models\Router', 'id_router');
    }

    // Relación: Equipo - Router (1 - 0/1)
    public function ips()
    {
        return $this->hasOne('App\Models\Ip', 'ip');
    }


    // Relación: Equipo - Impresora (1 - 0/1)
    public function impresoras()
    {
        return $this->hasOne('App\Models\Impresora', 'id_equipo');
    }
    

    // Relación: Equipo - DetalleEquipo (1 - 0/1)
    public function detalle_equipos()
    {
        return $this->hasOne('App\Models\DetalleEquipo', 'id_equipo');
    }

    public function detalle_componentes(){
        return $this->hasMany('App\Models\DetalleComponente', 'id_equipo');
        
    }

    // Relación: Marca - Equipo  (1 - M)
    public function marcas()
    {
        return $this->belongsTo('App\Models\Marca', 'id_marca');
    }

    // Relacion: Equipo - Estado (1 - 1)
    public function estados() {
        return $this->hasOne('App\Models\EstadoEquipo', 'id_estado_equipo');
    }
}