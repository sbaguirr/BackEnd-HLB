<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleComponente extends Model
{
    protected $table = 'detalle_componentes';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dato','campo','id_componente'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];
   
    public function equipos()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo');
    }
}
