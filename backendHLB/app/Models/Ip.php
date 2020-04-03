<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $table = 'ips';
    protected $primaryKey = 'id_ip';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estado', 'direccion_ip', 'hostname',
        'subred', 'fortigate', 'observacion', 'maquinas_adicionales',
        'nombre_usuario', 'encargado_registro', 'created_at'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	 'updated_at'
    ];


    // Relación: Usuario - Ip (1 - M)
    public function usuarios()
    {
        return $this->belongsTo('App\Models\Usuario', 'encargado_registro');
    }

    
    // Relación: Ip - Equipo (1 - 0/1)
    public function equipos()
    {
        return $this->hasOne('App\Models\Equipo', 'ip');
    }
}
