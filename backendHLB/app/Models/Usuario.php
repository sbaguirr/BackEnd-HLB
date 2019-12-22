<?php

namespace App\app\Models;

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
        return $this->belongsTo('App\Models\Roles', 'id_rol');
    }


    // Relación: Empleado - Usuario (1 - 1)
    public function empleados()
    {
        return $this->belongsTo('App\Models\Empleado', 'cedula');
    }
}
