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
        'direccion_ip', 'hostname',
        'subred', 'fortigate', 'observacion', 'maquinas_adicionales',
        'nombre_usuario', 'encargado_registro', 'estado'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    // Relación: Usuario - Ip (1 - M)
    public function usuarios()
    {
        return $this->belongsTo('App\Models\User', 'encargado_registro');
    }

}
