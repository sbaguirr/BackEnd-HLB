<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mostrar_todos()
    {
        return Tipo::all();
    }

    public function crear_tipo(Request $request)
    {
        $tipo_equipo = new Tipo();
        $tipo_equipo->tipo= $request->get('tipo');
        $tipo_equipo->usa_ip= $request->get('usa_ip');
        $tipo_equipo->save();
    }


}
