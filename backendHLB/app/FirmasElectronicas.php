<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirmasElectronicas extends Model
{
    //

    protected $table = 'firmas_electronicas';
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_name','image_url'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at'
    ];


    public function solicitudes()
    {
        return $this->belongsTo('App\Models\Solicitud', 'id_solicitud');
    }



}
