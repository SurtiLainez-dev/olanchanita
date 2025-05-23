<?php

namespace App\Http\Controllers;

use App\Models\CuerpoOrdenPorcionado;
use App\Models\OrdenPorcionado;
use App\Models\StockArticulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdenPorcionController extends Controller
{
    public function index(){
        $ordenes = OrdenPorcionado::with(['user','sucursal'])->get();

        return response()->json(['ordenes'=>$ordenes],200);
    }

    public function store(Request $request){
        $nuevaOrden              = new OrdenPorcionado();
        $nuevaOrden->user_id     = Auth::user()->id;
        $nuevaOrden->articulo_id = $request->articulo;
        $nuevaOrden->comentario  = $request->comentario;
        $nuevaOrden->sucursal_id = $request->sucursal_id;
        $nuevaOrden->save();

        foreach ($request->cuerpoOrden as $cuerpo):
            $nuevoRegistroCuerpoOrden                           = new CuerpoOrdenPorcionado();
            $nuevoRegistroCuerpoOrden->orden_porcionado_id      = $nuevaOrden->id;
            $nuevoRegistroCuerpoOrden->articulo_id              = $cuerpo['articulo_id'];
            $nuevoRegistroCuerpoOrden->cantidad_actual_articulo = $cuerpo['stock_actual'];
            $nuevoRegistroCuerpoOrden->cantidad                 = $cuerpo['stock_agregado'];
            $nuevoRegistroCuerpoOrden->cantidad_nueva           = $cuerpo['stock_nuevo'];
            $nuevoRegistroCuerpoOrden->comentario               = $cuerpo['comentario'];
            $nuevoRegistroCuerpoOrden->sucursal_id              = $cuerpo['sucursal_id'];
            $nuevoRegistroCuerpoOrden->save();

            if ($cuerpo['stock_id'] > 0):
                $editStock               = StockArticulo::find($cuerpo['stock_id']);
                $editStock->stock_actual = $cuerpo['stock_agregado'] + $editStock->stock_actual;
                $editStock->save();
            else:
                $nuevoStock                = new StockArticulo();
                $nuevoStock->articulo_id   = $cuerpo['articulo_id'];
                $nuevoStock->stock_actual  = $cuerpo['stock_nuevo'];
                $nuevoStock->sucursal_id   = $cuerpo['sucursal_id'];
                $nuevoStock->stock_inicial = 0;
                $nuevoStock->save();

                ArticuloController::addHistorialArticulo($cuerpo['articulo_id'],'SE CREÓ REGISTRO DE STOCK ', $cuerpo['sucursal_id']);
            endif;
            ArticuloController::addHistorialArticulo($cuerpo['articulo_id'],'SE CARGÓ UNA CANTIDAD DE '.$cuerpo['stock_agregado'].' PORCIONES DE LA ORDEN #'.$nuevaOrden->id, $cuerpo['sucursal_id']);

        endforeach;

        $stockArticuloOrden                   = StockArticulo::where('sucursal_id', $request->sucursal_id)->where('articulo_id', $request->articulo)->first();
        $editStockArticuloOrden               = StockArticulo::find($stockArticuloOrden->id);
        $editStockArticuloOrden->stock_actual = $editStockArticuloOrden->stock_actual - $request->cantidad;
        $editStockArticuloOrden->save();

        ArticuloController::addHistorialArticulo($cuerpo['articulo_id'],'SE REBAJA STOCK DE'.$request->cantidad.' PARA PORCIONES. ORDEN #'.$nuevaOrden->id, $cuerpo['sucursal_id']);


        return response()->json(['msj'=>'Se ha registrado la orden exitosamente'],200);
    }
}
