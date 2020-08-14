<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    //

    protected $table = 'recordatorios';
    protected $primaryKey = 'id_recordatorio';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hora_recordatorio','fecha_recordatorio','estado', 'id_mantenimiento'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: Mantenimiento - Recordatorio (1 - 0/1)
    public function mantenimientos()
    {
        return $this->belongsTo('App\Models\Mantenimiento', 'id_mantenimiento');
    }

}
