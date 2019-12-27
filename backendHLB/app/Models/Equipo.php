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
        'fecha_registro','estado_asignacion','codigo','tipo_equipo','encargado_registro','ip'
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


    // Relación: Ip - Equipo (1 - 0/1)
    public function ips()
    {
        return $this->belongsTo('App\Models\Ip', 'ip');
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


    // Relación: Equipo - Router (1 - 0/1)
    public function routers()
    {
        return $this->hasOne('App\Models\Router', 'id_equipo');
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
}