<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Marca;

class MarcaController extends Controller
{
    public function listado_marcas()
    {
        return Marca::all();
    }
}
