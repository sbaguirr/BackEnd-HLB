<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoEquipo extends Model
{
    protected $table = 'estado_equipo';
    protected $primaryKey = 'id_estado_equipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'abreviatura', 'id_equipo', 'id_ip'
    ];

    /**
     * The attributes that shou ld be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];

    // Relacion: Equipo - Estado (1 - 1)
    public function equipos() {
        return $this->hasOne('App\Models\Equipo', 'id_equipo');
    }

    // Relacion: Ip - Estado (1 - 1)
    public function ips() {
        return $this->hasOne('App\Models\Ip', 'id_ip');
    }
}
