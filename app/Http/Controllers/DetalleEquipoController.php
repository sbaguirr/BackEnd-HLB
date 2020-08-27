<?php

namespace App\Http\Controllers;

use App\Models\DetalleEquipo;
use Illuminate\Http\Request;

class DetalleEquipoController extends Controller
{
    public function listar_so()
    {
        $sistemas_operativos = array();
        $so = DetalleEquipo::select('so')->distinct()->get();
        for ( $i=0; $i<count($so); $i++ ){
            array_push($sistemas_operativos, $so[$i]['so']);
        }
        return ($sistemas_operativos);
    }

    public function listar_office()
    {
        $listado_office = array();
        $office = DetalleEquipo::select('office')->distinct()->get();
        for ( $i=0; $i<count($office); $i++ ){
            array_push($listado_office, $office[$i]['office']);
        }
        return ($listado_office);
    }
}
