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

    public function crear_marca(Request $request){
        $marca= new Marca();
        $marca->nombre= $request->get('nombre');
        $marca->save();
    }

    public function editar_marca(Request $request){
        $marca = Marca::find($request->get('key')); 
        $marca->nombre= $request->get('nombre');
        $marca->save();
    }

}
