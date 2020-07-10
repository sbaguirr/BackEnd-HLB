<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudEquipo extends Model
{
    //

    protected $table = 'solicitud_equipos';
    protected $primaryKey = 'id_solicitud_equipo';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
        'id_solicitud', 'id_equipo'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // Relación: SolicitudEquipo - Equipos (1 - M)
    public function equipos()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo');
    }

    // Relación: SolicitudEquipo - Solicitud (1 - M)
    public function solicitudes()
    {
        return $this->belongsTo('App\Models\Solicitud', 'id_solicitud');
    }
}
