<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\TipoGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    public function storeTipoGasto(Request $request){
        $nuevoTipoGasto = new TipoGasto();
        $nuevoTipoGasto->nombre = $request->nombre;
        $nuevoTipoGasto->save();

        return  response()->json(['msj'=>'Se ha creado el gasto correctamente'],200);
    }

    public function indexTipoGasto(){
        return response()->json(['tipo_gastos'=>TipoGasto::all()],200);
    }

    public static function storeGasto($tipoGasto, $user, $caja, $banco, $detalle, $total){
        $nuevoGasto = new Gasto();
        $nuevoGasto->tipo_gasto_id = $tipoGasto;
        $nuevoGasto->user_id       = $user;
        $nuevoGasto->caja_id       = $caja;
        $nuevoGasto->banco_id      = $banco;
        $nuevoGasto->detalle       = $detalle;
        $nuevoGasto->total         = $total;
//        if ($request->hasFile('file'))
//            S3::cargarFileS3($request->file('file'),'...gastos/', 'private');
        $nuevoGasto->save();

        return $nuevoGasto->id;
    }
}
