<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramaInstalado extends Model
{
    protected $table = 'programas_instalados';
    protected $primaryKey = 'id_programa';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'codigo', 'observacion', 'encargado_registro'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: Usuario - ProgramaInstalado (1 - M)
    public function usuarios()
    {
        return $this->belongsTo('App\Models\Usuario', 'encargado_registro');
    }


    // Relación: ProgramaInstalado - ProgramaEquipo (1 - M)
    public function programa_equipos()
    {
        return $this->hasMany('App\Models\ProgramaEquipo', 'id_programa');
    }
}
