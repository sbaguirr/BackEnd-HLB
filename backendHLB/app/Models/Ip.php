<?php

namespace App\app\Models;

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
        'estado','fecha_asignación','direccion_ip','hostname',
        'subred','fortigate', 'observacion', 'maquinas_adicionales',
        'nombre_usuario','encargado_registro' 
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];
}
