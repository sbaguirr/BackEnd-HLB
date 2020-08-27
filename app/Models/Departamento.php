<?php

namespace App\Models;

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


    // Relación: Organizacion - Departamento (1 - M)
    public function organizaciones()
    {
        return $this->belongsTo('App\Models\Organizacion', 'id_organizacion');
    }

    
    // Relación: Departamento - Empleado (1 - M)
    public function empleados()
    {
        return $this->hasMany('App\Models\Empleado', 'id_departamento');
    }
}
