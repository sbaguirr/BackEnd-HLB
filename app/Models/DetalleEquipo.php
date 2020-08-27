<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEquipo extends Model
{
    protected $table = 'detalle_equipos';
    protected $primaryKey = 'id_detalle';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'so','services_pack','tipo_so','nombre_pc','id_equipo'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: Equipo - DetalleEquipo (1 - 0/1)
    public function equipos()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo');
    }
}


