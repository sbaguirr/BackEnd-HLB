<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario';

    /**
     * Indicates if the IDs are no auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'contrasena', 'id_rol', 'cedula'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'constrasena','created_at', 'updated_at'
    ];


    // Relación: Rol - Usuario (1 - M)
    public function roles()
    {
        return $this->belongsTo('App\Models\Rol', 'id_rol');
    }


    // Relación: Empleado - Usuario (1 - 1)
    public function empleados()
    {
        return $this->belongsTo('App\Models\Empleado', 'cedula');
    }
    

    // Relación: Usuario - ProgramaInstalado (1 - M)
    public function programas_instalados()
    {
        return $this->hasMany('App\Models\ProgramaInstalado', 'encargado_registro');
    }


    // Relación: Usuario - Ip (1 - M)
    public function ips()
    {
        return $this->hasMany('App\Models\Ip', 'encargado_registro');
    }


    // Relación: Usuario - Equipo (1 - M)
     public function equipos()
    {
        return $this->hasMany('App\Models\Equipo', 'encargado_registro');
    } 
}
