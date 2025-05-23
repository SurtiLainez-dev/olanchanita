<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CuerpoOrdenSalida;
use App\Models\OrdenSalida;
use App\Models\StockArticulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdenSalidaController extends Controller
{
    public function store(Request $request){
        $nuevaOrden               = new OrdenSalida();
        $nuevaOrden->user_id      = Auth::user()->id;
        $nuevaOrden->sucursal_id  = $request->sucursal_id;
        $nuevaOrden->comentario   = $request->comentario;
        $nuevaOrden->total_salida = $request->total;
        $nuevaOrden->save();

        foreach ($request->articulos as $articulo):
            $nuevoRegistro                  = new CuerpoOrdenSalida();
            $nuevoRegistro->orden_salida_id = $nuevaOrden->id;
            $nuevoRegistro->articulo_id     = $articulo['id'];
            $nuevoRegistro->cantidad        = $articulo['stock'];
            $nuevoRegistro->precio          = $articulo['total'];
            $nuevoRegistro->comentario      = $articulo['comentario'];
            $nuevoRegistro->save();

            ArticuloController::addHistorialArticulo($articulo['id'],'SE REGISTRÓ EN LA ORDEN DE SALIDA #'.$nuevaOrden->id,$request->sucursal_id);
            if ($nuevoRegistro->cantidad > 0){
                $stock = StockArticulo::where([['articulo_id','=', $articulo['id']],['sucursal_id','=',$request->sucursal_id]])->first();
                $art   = Articulo::where('id', $articulo['id'])->first();
                if ($stock):
                    if ($art->is_contable == 1):
                        $editStock = StockArticulo::find($stock->id);
                        $editStock->stock_actual = $editStock->stock_actual - $articulo['stock'];
                        $editStock->save();
                        ArticuloController::addHistorialArticulo($articulo['id'],'SE REDUJO EL STOCK EN LA ORDEN #'.$nuevaOrden->id,$request->sucursal_id);
                    endif;
                endif;
            }
        endforeach;

        return response()->json(['msj'=>'Se ha registrado exitosamente la orden'],200);
    }

    public function index(){
        $ordenes = OrdenSalida::with([
            'user',
            'sucursal'
        ])->get();

        return response()->json(['ordenes'=>$ordenes],200);
    }

    public function show($id){
        $orden = OrdenSalida::with([
            'cuerpo_orden_salidas' => function($query){
                $query->with([
                    'articulo'
                ]);
            },
            'user',
            'sucursal'
        ])->where('id', $id)
            ->first();

        return response()->json(['orden'=>$orden],200);
    }
}
