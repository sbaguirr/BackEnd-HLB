<?php

namespace App\app\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{

    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','id_organizacion'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];

/*
    // Relación: Rol - Usuario (1 - M)
    public function roles()
    {
        return $this->hasMany('App\Models\Empleado');
    }
*/
}
