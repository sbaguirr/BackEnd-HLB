<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramaEquipo extends Model
{
    protected $table = 'programa_equipos';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_instalacion','id_programa','id_equipo'
       ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: ProgramaInstalado - ProgramaEquipo (1 - M)
    public function programas_instalados()
    {
        return $this->belongsTo('App\Models\ProgramaInstalado', 'id_programa');
    }


    // Relación: Equipo - ProgramaEquipo (1 - M)
    public function equipos()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo');
    }
}
