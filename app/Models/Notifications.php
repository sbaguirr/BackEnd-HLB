<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    //
    protected $table = 'marcas';
    protected $primaryKey = 'id';


    protected $fillable = [
        'message',"user_id"
    ];

    protected $hidden = [
    	'created_at', 'updated_at'
    ];
}
