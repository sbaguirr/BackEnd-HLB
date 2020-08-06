<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'username';

    /**
     * Indicates if the IDs are no auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'id_rol', 'cedula', 'created_at', 'device_token'
        //'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsTo('App\Models\Rol', 'id_rol');
    }


    // Relación: Empleado - Usuario (1 - 1)
    public function empleados()
    {
        return $this->belongsTo('App\Models\Empleado', 'cedula');
    }


    // Relación: Usuario - ProgramaInstalado (1 - M)
    public function programas_instalados()
    {
        return $this->hasMany('App\Models\ProgramaInstalado', 'encargado_registro');
    }


    // Relación: Usuario - Ip (1 - M)
    public function ips()
    {
        return $this->hasMany('App\Models\Ip', 'encargado_registro');
    }


    // Relación: Usuario - Equipo (1 - M)
     public function equipos()
    {
        return $this->hasMany('App\Models\Equipo', 'encargado_registro');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relación: Usuario - Solicitud (1 - M)
    public function solicitudes(){
        return $this->hasMany('App\Models\Solicitud', 'id_usuario');
    }

    // Relación: Usuario - AtencionSolicitud (1 - M)
    public function atencion_solicitudes(){
        return $this->hasMany('App\Models\AtencionSolicitud', 'id_usuario');
    }

     // Relación: Empleado - Correo (1 - M)
     public function mantenimiento()
     {
         return $this->hasMany('App\Models\Mantenimiento', 'username');
     }
}



