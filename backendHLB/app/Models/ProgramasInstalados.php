<?php

namespace App\app\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramasInstalados extends Model
{
    protected $table = 'programas_instalados';
    protected $primaryKey = 'id_programa';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','codigo','observacion','encargado_registro'
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
