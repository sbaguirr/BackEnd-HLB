<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoExtra extends Model
{
    protected $table = 'equipos_extras';
    protected $primaryKey = 'id_eqext';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'num_serie','marca','modelo','estado_operativo','descripcion','id_equipo'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];
}
