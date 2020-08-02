<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    //

    protected $table = 'solicitudes';
    protected $primaryKey = 'id_solicitud';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'observacion', 'tipo',
        'prioridad', 'estado', 'fecha_realizacion', 'hora_realizacion',
        'id_firma', 'id_usuario'
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

    public function firmas_electronicas()
    {
        return $this->hasOne('App\Models\FirmasElectronicas', 'id');
    }

    // Relación: Solicitud - AtencionSolicitud (1 - M)
    public function atencion_solicitudes()
    {
        return $this->hasMany('App\Models\AtencionSolicitud', 'id_solicitud');
    }

    public static function contar_pendientes(){
        return Solicitud::where('estado', 'P')->get()->count();
    }
}
