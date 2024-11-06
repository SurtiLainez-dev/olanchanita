<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CuerpoOrdenEntrada;
use App\Models\OrdenEntrada;
use App\Models\StockArticulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenEntradasController extends Controller
{
    public function store(Request $request){
        $nuevaOrdenEntrada = new OrdenEntrada();
        $nuevaOrdenEntrada->tipo_entrada_articulo_id = $request->tipo;
        $nuevaOrdenEntrada->proveedor_id             = $request->proveedor;
        $nuevaOrdenEntrada->user_id                  = Auth::user()->id;
        $nuevaOrdenEntrada->sucursal_id              = $request->sucursal;
        $nuevaOrdenEntrada->fecha_creacion           = date('Y-m-d');
        $nuevaOrdenEntrada->observacion              = $request->observacion;
        $nuevaOrdenEntrada->codigo                   = self::crearCodigos(OrdenEntrada::count(),'SL-D1-');
//        if ($request->tipo === 1)
//            $nuevaOrdenEntrada->estado               = false;
//        else
//            $nuevaOrdenEntrada->estado               = true;
        $nuevaOrdenEntrada->save();

        foreach ($request->articulos as $articulo):
            $nuevoRegistroOrden = new CuerpoOrdenEntrada();
            $nuevoRegistroOrden->orden_entrada_id   = $nuevaOrdenEntrada->id;
            $nuevoRegistroOrden->articulo_id        = $articulo['articulo'];
            $nuevoRegistroOrden->cantidad           = $articulo['cantidad'];
            $nuevoRegistroOrden->estado_articulo_id = $articulo['estado'];
            $nuevoRegistroOrden->save();

            $Art = Articulo::where('id', $articulo['articulo'])->first();
            if ($Art->is_contable === 1):
                if (StockArticulo::where('articulo_id', $articulo['articulo'])->where('sucursal_id',$request->sucursal)->count() > 0):
                    $stock                   = StockArticulo::where('articulo_id', $articulo['articulo'])->where('sucursal_id',$request->sucursal)->first();
                    $editStock               = StockArticulo::find($stock->id);
                    $editStock->stock_actual = $editStock->stock_actual + $articulo['cantidad'];
                    $editStock->save();
                else:
                    $nuevoStock = new StockArticulo();
                    $nuevoStock->articulo_id   = $articulo['articulo'];
                    $nuevoStock->stock_actual  = $articulo['cantidad'];
                    $nuevoStock->sucursal_id   = $request->sucursal;
                    $nuevoStock->stock_inicial = 0;
                    $nuevoStock->save();
                    ArticuloController::addHistorialArticulo($articulo['articulo'],'SE CREÓ REGISTRO DE STOCK ', $request->sucursal);
                endif;
                ArticuloController::addHistorialArticulo($articulo['articulo'],'SE CARGARON '.$articulo['cantidad'].' ITEMS. ORDEN DE ENTRADA #'.$nuevaOrdenEntrada->codigo , $request->sucursal);
            endif;
        endforeach;

        return response()->json(['msj'=>'Se ha registrado la orden exitosamente'],200);
    }

    public function index(){
        $entradas = DB::table('orden_entradas')
            ->join('proveedors','proveedors.id','=', 'orden_entradas.proveedor_id')
            ->join('tipo_entrada_articulos', 'tipo_entrada_articulos.id','=','orden_entradas.tipo_entrada_articulo_id')
            ->join('users','users.id','=','orden_entradas.user_id')
            ->join('sucursals', 'sucursals.id','=', 'orden_entradas.sucursal_id')
            ->select('proveedors.nombre as proveedor','users.usuario','tipo_entrada_articulos.nombre as entrada',
                'sucursals.nombre as sucursal', 'orden_entradas.fecha_creacion', 'orden_entradas.codigo',
                'orden_entradas.id','orden_entradas.observacion')
            ->orderBy('orden_entradas.created_at','DESC')
            ->get();
        return response()->json(['ordenes'=>$entradas], 200);
    }

    public function pendientesFactura(){
        $entradas = DB::table('orden_entradas')
            ->join('proveedors','proveedors.id','=', 'orden_entradas.proveedor_id')
            ->join('tipo_entrada_articulos', 'tipo_entrada_articulos.id','=','orden_entradas.tipo_entrada_articulo_id')
            ->join('users','users.id','=','orden_entradas.user_id')
            ->join('sucursals', 'sucursals.id','=', 'orden_entradas.sucursal_id')
            ->where('orden_entradas.estado', false)
            ->select('proveedors.nombre as proveedor','users.usuario','tipo_entrada_articulos.nombre as entrada',
                'sucursals.nombre as sucursal', 'orden_entradas.fecha_creacion', 'orden_entradas.codigo',
                'orden_entradas.id','orden_entradas.observacion','orden_entradas.estado')
            ->orderBy('orden_entradas.created_at','DESC')
            ->get();
        return response()->json(['ordenes'=>$entradas], 200);
    }

    public static function crearCodigos($cantidad, $cod){
        $primeraParte = $cod;
        if ($cantidad <= 9){
            $segundaParte='00000-'.$cantidad;
        }elseif ($cantidad >= 10 && $cantidad <=99){
            $segundaParte='0000-'.$cantidad;
        }elseif ($cantidad >= 100 && $cantidad <=999){
            $segundaParte='000-'.$cantidad;
        }elseif ($cantidad >= 1000 && $cantidad <= 9999){
            $segundaParte='00-'.$cantidad;
        }elseif ($cantidad >= 10000 && $cantidad <= 99999){
            $segundaParte='0-'.$cantidad;
        }elseif ($cantidad >= 100000 && $cantidad <= 999999){
            $segundaParte='-'.$cantidad;
        }
        return $primeraParte.$segundaParte;
    }

    public function show($orden){
        $orden = OrdenEntrada::where('id', $orden)->with([
            'user' => function($query){
                $query->with(['colaborador']);
            },
            'cuerpo_orden_entradas' => function($query){
                $query->with([
                    'articulo' => function($query){
                        $query->with(['marca']);
                    },
                    'estado_articulo',
                ]);
            }
        ])->first();
        return response()->json(['orden'=>$orden], 200);
    }
}
