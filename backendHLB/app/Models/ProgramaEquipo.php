<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramaEquipo extends Model
{
    protected $table = 'programa_equipos';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_instalacion','id_programa','id_equipo'
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
