<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use Illuminate\Http\Request;

class OrganizacionController extends Controller
{
    
    public function mostrar_todos()
    {
        return Organizacion::select('bspi_punto')
        ->get();
    }
}
