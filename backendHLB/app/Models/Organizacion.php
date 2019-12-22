<?php

namespace App\app\Models;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{

    protected $table = 'organizaciones';
    protected $primaryKey = 'id_organizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bspi_punto'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    // Relación: Organizacion - Departamento (1 - M)
    public function departamentos()
    {
        return $this->hasMany('App\Models\Departamento');
    }

}
