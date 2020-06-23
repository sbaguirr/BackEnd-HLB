<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    protected $table = 'routers';
    protected $primaryKey = 'id_router';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','pass','puerta_enlace','clave','estado_operativo', 'created_at', 'id_equipo'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'updated_at'
    ];
   

    // Relación: Equipo - Router (1 - 0/1)
    public function equipos()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo');
    }
}
