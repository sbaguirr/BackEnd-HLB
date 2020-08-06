<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimientos';
    protected $primaryKey = 'id_mantenimiento';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'tipo', 'fecha_inicio', 'fecha_fin','observacion_falla','estado_fisico','actividad_realizada',
        'observacion','id_equipo','id_solicitud','realizado_por','created_at'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	 'updated_at'
    ];

     // Relación: Equipo - Mantenimiento (1 - M)
     public function equipo()
     {
         return $this->belongsTo('App\Models\Equipo', 'id_equipo');
     }

      // Relación: Equipo - Mantenimiento (1 - M)
      public function solicitud()
      {
          return $this->belongsTo('App\Models\Solicitud', 'id_solicitud');
      }

      // Relación: Equipo - Mantenimiento (1 - M)
      public function usuario()
      {
          return $this->belongsTo('App\Models\User', 'username');
      }


}
