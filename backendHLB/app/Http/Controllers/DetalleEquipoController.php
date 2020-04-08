<?php

namespace App\Http\Controllers;

use App\Models\DetalleEquipo;
use Illuminate\Http\Request;

class DetalleEquipoController extends Controller
{
    public function listar_so()
    {
        return DetalleEquipo::select('so')->distinct()->get();
    }
}
