<?php

namespace App\Http\Controllers;

use App\Models\DirectrizImpresion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectricezImpresionController extends Controller
{
    public function index(){
        return response()->json([
            'directrices' => DirectrizImpresion::with(['sucursal','user'])->get()
        ], 200);
    }

    public function store_directriz(Request  $request){
        $cont = DirectrizImpresion::where('tipo', $request->tipo)->where('sucursal_id', $request->sucursa_id)->count();
        if ($cont > 0):
            $directrices = DirectrizImpresion::where('tipo', $request->tipo)->where('sucursal_id', $request->sucursal_id)->get();
            foreach ($directrices as $directrice):
                $update = DirectrizImpresion::find($directrice->id);
                $update->estado = false;
            endforeach;
        endif;

        $nuevaDirectriz                       = new DirectrizImpresion();
        $nuevaDirectriz->sucursal_id          = $request->sucursal_id;
        $nuevaDirectriz->contador_inicial     = $request->contador_inicial;
        $nuevaDirectriz->contador_final       = $request->contador_final;
        $nuevaDirectriz->codigo_post_contador = $request->codigo_post;
        $nuevaDirectriz->fecha_emision        = $request->fecha_emision;
        $nuevaDirectriz->fecha_final          = $request->fecha_final;
        $nuevaDirectriz->estado               = true;
        $nuevaDirectriz->cai                  = $request->cai;
        $nuevaDirectriz->tipo                 = $request->tipo;
        $nuevaDirectriz->user_id              = Auth::user()->id;
        $nuevaDirectriz->inicio_contador      = $request->inicio_contador;
        $nuevaDirectriz->contador_actual      = 0;
        $nuevaDirectriz->save();

        return response()->json(['msj'=>'Se ha agragado la nueva directríz de impresión de documentos.'],200);
    }
}
