<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'cedula';

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
        'nombre','apellido','id_departamento'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: Departamento - Empleado (1 - M)
    public function departamentos()
    {
        return $this->belongsTo('App\Models\Departamento', 'id_departamento');
    }
  

    // Relación: Empleado - Correo (1 - M)
    public function correos()
    {
        return $this->hasMany('App\Models\Correo', 'cedula');
    }


    // Relación: Empleado - Usuario (1 - 1)
    public function usuarios()
    {
        return $this->hasOne('App\Models\Usuario', 'cedula');
    }

    // Relación: Usuario - Equipo (1 - M)
    public function equipos()
    {
        return $this->hasMany('App\Models\Equipo', 'asignado');
    }

}
