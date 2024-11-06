<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesasController extends Controller
{
    public function store (Request  $request){
        $nuevaMesa = new Mesa();
        $nuevaMesa->nombre        = $request->nombre;
        if ($request->is_redundante == true || $request->is_redundante == 'true')
            $nuevaMesa->num       = 0;
        else
            $nuevaMesa->num       = Mesa::where('is_redundante',0)->count() + 1;
        $nuevaMesa->is_redundante = $request->is_redundante;
        $nuevaMesa->save();

        return response()->json(['msj'=>'Se ha creado la mesa exitosamente'],200);
    }

    public function index(){
        return response()->json(['mesas'=>Mesa::all()],200);
    }
}
