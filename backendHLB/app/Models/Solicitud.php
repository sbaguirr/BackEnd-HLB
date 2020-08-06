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

    public static function contar_pendientes(){
        return Solicitud::where('estado', 'P')->get()->count();
    }

    // Relación: Empleado - Correo (1 - M)
    public function mantenimiento()
    {
        return $this->hasMany('App\Models\Mantenimiento', 'id_solicitud');
    }
}
