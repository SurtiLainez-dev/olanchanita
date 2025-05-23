<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionImpresora;
use Illuminate\Http\Request;

class ConfiguracionImpresoras extends Controller
{
    public function index(){
        return response()->json(['impresoras'=>ConfiguracionImpresora::all()],200);
    }

    public function store(Request $request){
        $nuevaImpresora = new ConfiguracionImpresora();
        $nuevaImpresora->token = $request->token;
        $nuevaImpresora->nombre_trabajo = $request->nombre;
        $nuevaImpresora->id_print_node  = $request->id;
        $nuevaImpresora->save();

        return response()->json(['msj'=>'Se ha registrado exitosamente la impresora'],200);
    }

    public function update(Request $request, $id){
        $editImpresora = ConfiguracionImpresora::find($id);
        $editImpresora->token = $request->token;
        $editImpresora->nombre_trabajo = $request->nombre;
        $editImpresora->id_print_node  = $request->id;
        $editImpresora->save();

        return response()->json(['msj'=>'Se ha actualizado exitosamente la impresora'],200);
    }
}
