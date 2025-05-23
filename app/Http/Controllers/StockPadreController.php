<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\StockPadre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockPadreController extends Controller
{
    public function store(Request $request){
        DB::table('stock_padres')->where('articulo_id', $request->articuloHijo)
            ->update(['activo'=>false]);
        DB::table('articulos')->where('id',$request->articuloHijo)
            ->update(['is_contable'=>false,'is_visible'=>true]);

        $nuevoStockPadre = new StockPadre();
        $nuevoStockPadre->articulo_id       = $request->articuloHijo;
        $nuevoStockPadre->articulo_padre_id = $request->articuloPadre;
        $nuevoStockPadre->save();

        ArticuloController::addHistorialArticulo($request->articuloHijo, 'SE HA AGREGADO AL ARTÍCULO #'.$request->codigoPadre.' COMO PADRE EN EL STOCK', null);
        ArticuloController::addHistorialArticulo($request->articuloPadre,'SE HA AGREGADO AL ARTÍCULO #'.$request->codigoHijo.' COMO HIJO EN EL STOCK', null);

        return response()->json(['msj'=>'Se ha registro el stock padre del artículo'],200);
    }

    public function show($id){
        $stockPadres = StockPadre::with([
            'articulo',
            'articulo_padre'
        ])->where('articulo',$id)
            ->get();

        return response()->json(['stockPadres'=>$stockPadres],200);
    }

    public function update(Request $request, $id){
        DB::table('stock_padres')->where('articulo_id', $id)
            ->update(['activo'=>false]);
        ArticuloController::addHistorialArticulo($id,'SE LE DESACTIVÓ EL ÚLTIMO PADRA DE STOCK VINCULADO', null);

        return response()->json(['msj'=>'Se ha actualizado removido el último padre de stock activo'],200);
    }
}
