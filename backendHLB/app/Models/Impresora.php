<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impresora extends Model
{
    protected $table = 'impresoras';
    protected $primaryKey = 'id_impresora';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo','marca','modelo','numero_serie','tinta','cartucho','estado_operativo','id_equipo'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: Equipo - Impresora (1 - 0/1)
    public function equipos()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo');
    }
}
