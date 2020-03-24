<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipos';
    protected $primaryKey = 'id_tipo';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo','uso_ip'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];
 // Relación: Equipo - Tipo de equipo (1 - 1)
    public function tipo()
    {
        return $this->hasOne('App\Models\Equipo', 'id_tipo');
    }

}
