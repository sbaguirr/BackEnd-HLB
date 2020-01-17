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

        public function org_dpto($punto)
    {
        return Departamento::select('id_departamento', 'nombre')
        ->join('organizaciones','organizaciones.id_organizacion','=','departamentos.id_organizacion')
        ->where('organizaciones.bspi_punto',$punto)
        ->get()
        ;
    } 
}
