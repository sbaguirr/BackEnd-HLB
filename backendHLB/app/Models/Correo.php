<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    protected $table = 'correos';
    protected $primaryKey = 'id_correo';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'correo', 'contrasena', 'estado', 'cedula'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'constrasena','created_at', 'updated_at'
    ];

    
    // Relación: Empleado - Correo (1 - M)
    public function empleados()
    {
        return $this->belongsTo('App\Models\Empleado', 'cedula');
    }
}
