<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionSolicitud extends Model
{
    //

    protected $table = 'atencion_solicitudes';
    protected $primaryKey = 'id_atencion';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_atencion','hora_atencion', 'observacion',
        'id_solicitud', 'id_usuario'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // Relación: Usuario - Solicitud (1 - M)
    public function usuarios()
    {
        return $this->belongsTo('App\Models\User', 'id_usuario');
    }

    // Relación: Solicitud - AtencionSolicitud (1 - M)
    public function solicitudes()
    {
        return $this->belongsTo('App\Models\Solicitud', 'id_solicitud');
    }

    // Relación: Usuario - AtencionSolicitud (1 - M)
    public function users(){
        return $this->belongsTo('App\Models\Users', 'id_usuario');
    }
}
