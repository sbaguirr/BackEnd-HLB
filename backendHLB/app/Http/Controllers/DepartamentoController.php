<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    
    public function mostrar_todos()
    {
        return Departamento::select('nombre')
        ->distinct()
        ->get();
    }  
}
