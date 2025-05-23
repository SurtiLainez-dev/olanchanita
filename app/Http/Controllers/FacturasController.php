<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\CuerpoComanda;
use App\Models\CuerpoFactura;
use App\Models\DatosEmpresa;
use App\Models\DirectrizImpresion;
use App\Models\Factura;
use App\Models\HistorialCaja;
use App\Models\PrecioArticulo;
use App\Models\StockArticulo;
use App\Models\StockPadre;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Luecano\NumeroALetras\NumeroALetras;

class FacturasController extends Controller
{
    public function store(Request $request){
        if ($request->tipo == 1):
            $tipo = 2;
            $tipoHistorial = 1;
        elseif ($request->tipo == 0):
            $tipo = 3;
            $tipoHistorial = 5;
        endif;
        if (DirectrizImpresion::where('sucursal_id', $request->sucursal_id)->where('tipo', $tipo)->count()):
            $directriz    = self::directrizImpresion($request->sucursal_id, $tipo);
            $nuevaFactura = new Factura();
            $nuevaFactura->cliente_id = $request->cliente_id;
            $nuevaFactura->user_id    = Auth::user()->id;
            $nuevaFactura->directriz_impresion_id = $directriz->id;
            if ($request->tipo == 0):
                $nuevaFactura->tipo = 0;
                $nuevaFactura->cancelado = false;
                $nuevaFactura->cobrado   = 0;
                $nuevaFactura->cambio    = 0;
            else:
                $nuevaFactura->tipo = 1;
                $nuevaFactura->cancelado = true;
                $nuevaFactura->cobrado   = $request->cobrado;
                $nuevaFactura->cambio    = $request->cambio;
            endif;
            $nuevaFactura->contador = self::generarContador($directriz->contador_actual, $directriz->inicio_contador, $directriz->codigo_post_contador);
            $nuevaFactura->comanda_id = $request->comanda_id;
            $nuevaFactura->forma_pago_id = $request->forma_pago_id;
            $nuevaFactura->comentario    = $request->comentario;
            $nuevaFactura->descuento     = $request->descuento;
            $nuevaFactura->exonerado     = 0;
            $nuevaFactura->impuesto_1    = 0;
            $nuevaFactura->impuesto_2    = 0;
            $nuevaFactura->total_exento  = 0;
            $nuevaFactura->total         = $request->total;
            $nuevaFactura->grabado_1     = 0;
            $nuevaFactura->grabado_2     = 0;
            $nuevaFactura->save();

            $precioDescuento             = 0;
            $impuesto                    = 0;
            $grabado                     = 0;
            $taxIndividual               = 0;
            $taxTotal                    = 0;
            foreach ($request->cuerpo as $cuerpo):
                $taxIndividual = 0;
                $taxTotal      = 0;
                $nuevoRegistroFactura = new CuerpoFactura();
                //buscando el precio activo del articulo
                $precioActivo         = PrecioArticulo::with(['impuesto'])->where('is_activo', 1)->where('articulo_id',$cuerpo['id'])->first();

                if ($cuerpo['combo'] == true):
                    $imp = round($cuerpo['total'] - ($cuerpo['total']/1.053),2);
                    $impuesto = $impuesto + $imp;
                    $taxTotal = $imp;
                    $taxIndividual = round($imp/$cuerpo['cantidad'], 2);
                elseif($precioActivo && $cuerpo['cantidad'] > 0 && $cuerpo['total'] > 0):
                    $imp = round($cuerpo['total'] - ($cuerpo['total']/($precioActivo->impuesto->porcentaje + 1)),2);
                    $taxTotal = $imp;
                    $taxIndividual = round($imp/$cuerpo['cantidad'],2);
                    $impuesto = $impuesto + $imp;
                endif;


                if ($cuerpo['combo'] == true):
                    $nuevoRegistroFactura->combo_id   = $cuerpo['id'];
                else:
                    $nuevoRegistroFactura->articulo_id = $cuerpo['id'];

                    if (StockPadre::where('articulo_id', $cuerpo['id'])->where('activo', true)->count() > 0):
                        $stockPadre = StockPadre::where('articulo_id', $cuerpo['id'])->where('activo', true)->first();
                        if ($stockPadre):
                            $stockArticulo = StockArticulo::where('sucursal_id', $request->sucursal)->where('articulo_id', $stockPadre->articulo_padre_id)->first();
                            if ($stockArticulo):
                                $editStock               = StockArticulo::find($stockArticulo->id);
                                $editStock->stock_actual = $editStock->stock_actual - $cuerpo['cantidad'];
                                $editStock->save();
                                ArticuloController::addHistorialArticulo($cuerpo['id'],'SE RABAJA LA CANTIDAD DE '.$cuerpo['cantidad'].' EN FACTURA #'.$nuevaFactura->contador,$request->sucursal);
                            endif;
                        endif;
                    endif;

                    $this->rebajarStock($cuerpo['id'],$request->sucursal_id,$cuerpo['cantidad'],$nuevaFactura->contador);
                endif;


                $nuevoRegistroFactura->factura_id   = $nuevaFactura->id;
                $nuevoRegistroFactura->cantidad     = $cuerpo['cantidad'];
                $nuevoRegistroFactura->precio       = $cuerpo['precio'];
                $nuevoRegistroFactura->total        = $cuerpo['total'];
                $nuevoRegistroFactura->precio_sides = $cuerpo['precio_sides'];
                $nuevoRegistroFactura->sin_imp      = $taxIndividual;
                $nuevoRegistroFactura->sin_imp_total =  $taxTotal;
                if ($precioActivo)
                    $nuevoRegistroFactura->impuesto_id = $precioActivo->impuesto_id;
                else
                    $nuevoRegistroFactura->impuesto_id = 1;
                $nuevoRegistroFactura->save();
            endforeach;

            DB::table('facturas')->where('id', $nuevaFactura->id)
                ->update([
                    'total_exento'  => 0,
                    'impuesto_1'    => $impuesto,
                    'impuesto_2'    => 0,
                    'grabado_1'     => 0,
                    'grabado_2'     => 0,
                ]);

            if ($request->comanda)
                $this->editComanda($request->cuerpo, $request->comanda,$request->totalOriginal);
            $total = 0;
            if ($request->forma_pago_id >= 2 && $request->forma_pago_id <= 5 && $request->tipo == 1)
                $total = $request->total;

            $this->editCaja($request->caja_id, $total,$request->forma_pago_id,$nuevaFactura->contador,$tipoHistorial,$nuevaFactura->id, null,0, null);
            $this->sumarContadorFactura($directriz->id, $directriz->contador_actual);
            $clave = AuthDocumentosController::ejecutarClave();

            return  response()->json(['msj'=>'Se ha registrado exitosamente la factura','token'=>$clave->token,'contador'=>$nuevaFactura->contador],200);
        else:
            return response()->json(['msj'=>'No hay directricez de impresión actualmente'],422);
        endif;
    }

    public function rebajarStock($id, $sucursal, $cantidad, $contador){
        $articulo = Articulo::where('id', $id)->first();
        if ($articulo->is_contable == 1):
            $stockArticulo = StockArticulo::where('sucursal_id', $sucursal)->where('articulo_id', $id)->first();
            if ($stockArticulo):
                $editStock               = StockArticulo::find($stockArticulo->id);
                $editStock->stock_actual = $editStock->stock_actual - $cantidad;
                $editStock->save();
                ArticuloController::addHistorialArticulo($id,'SE RABAJA LA CANTIDAD DE '.$cantidad.' EN FACTURA #'.$contador,$sucursal);
            endif;
        endif;
    }

    public static function directrizImpresion($sucursal, $tipo){
        return DirectrizImpresion::where('estado', true)
            ->where('tipo', $tipo)
            ->where('sucursal_id', $sucursal)
            ->first();
    }

    public static  function generarContador($contador_actual, $contador_inicial, $postC){
        $contador_inicial = intval($contador_inicial);

        $contador_actual = $contador_actual + $contador_inicial;
        if ($contador_actual < 10):
            return $postC.'0000000'.$contador_actual;
        elseif($contador_actual > 9 && $contador_actual < 100):
            return $postC.'000000'.$contador_actual;
        elseif ($contador_actual > 99 && $contador_actual < 1000):
            return $postC.'00000'.$contador_actual;
        elseif ($contador_actual > 999 && $contador_actual < 10000):
            return $postC.'0000'.$contador_actual;
        elseif ($contador_actual > 9999 && $contador_actual < 100000):
            return $postC.'000'.$contador_actual;
        elseif ($contador_actual > 99999 && $contador_actual < 1000000):
            return $postC.'00'.$contador_actual;
        elseif ($contador_actual > 999999 && $contador_actual < 10000000):
            return $postC.'0'.$contador_actual;
        else:
            return $contador_actual;
        endif;
    }

    public function editComanda($cuerpo, $comanda_id, $totalOriginal){
        foreach ($cuerpo as $item):
            if ($item['combo'] != null):
                $registro = CuerpoComanda::where('comanda_id', $comanda_id)
                    ->where('cant_pendiente','>',0)
                    ->where('combo_id', $item['id'])
                    ->first();
            else:
                $registro = CuerpoComanda::where('comanda_id', $comanda_id)
                    ->where('cant_pendiente','>',0)
                    ->where('articulo_id', $item['id'])
                    ->first();
            endif;

            if ($registro):
                $editRegistroComanda = CuerpoComanda::find($registro->id);
                $editRegistroComanda->cant_pendiente = $editRegistroComanda->cant_pendiente - $item['cantidad'];
                $editRegistroComanda->save();
            endif;
        endforeach;
        $editComanda = Comanda::find($comanda_id);
        $editComanda->saldo_actual = $editComanda->saldo_actual - $totalOriginal;
        if ($editComanda->saldo_actual < 1)
            $editComanda->estado = 1;
        $editComanda->save();
    }

    public static function editCaja($caja, $total, $forma_pago, $ref, $tipo_doc, $factura, $recibo, $tipo, $banco){
        $editCaja = Caja::find($caja);
        if ($tipo == 0 && $forma_pago >= 2 && $forma_pago <= 4)
            $editCaja->total = $editCaja->total + $total;
        else if ($tipo == 1)
            $editCaja->total = $editCaja->total - $total;
        $editCaja->save();

        $nuevoHistorialCaja = new HistorialCaja();
        $nuevoHistorialCaja->caja_id = $caja;
        $nuevoHistorialCaja->forma_pago_id = $forma_pago;
        $nuevoHistorialCaja->referencia    = $ref;
        $nuevoHistorialCaja->tipo_documento = $tipo_doc;
        $nuevoHistorialCaja->factura_id     = $factura;
        $nuevoHistorialCaja->recibo_id      = $recibo;
        $nuevoHistorialCaja->total          = $total;
        $nuevoHistorialCaja->tipo           = $tipo;
        $nuevoHistorialCaja->retirada_efectivo_id = $banco;
        $nuevoHistorialCaja->save();
    }

    public static function sumarContadorFactura($id, $contadorActual){
        DB::table('directriz_impresions')->where('id', $id)
            ->update(['contador_actual'=>$contadorActual+1]);
    }

    public function index($fecha){
        $facturas = Factura::with([
            'cuerpo_factura'
        ])->whereDate('created_at',$fecha)
            ->get();

        return response()->json(['facturas'=>$facturas],200);
    }

    public function show($factura){
        $fac = Factura::with([
            'cuerpo_facturas' => function($query){
                $query->with(['articulo','combo','impuesto']);
            },
            'cliente',
            'forma_pago'
        ])->where('id', $factura)->first();

        return response()->json(['factura'=>$fac],200);
    }

    public function print_factura($user, $factura, $token){
        $factura = Factura::with([
            'directriz_impresion' => function($query){
                $query->with(['sucursal']);
            },
            'historial_caja' => function($query){
                $query->with(['caja']);
            },
            'user',
            'forma_pago',
            'cliente',
            'cuerpo_facturas' => function($query){
                $query->with(['combo','articulo']);
            }
        ])->where('contador', $factura)->first();
        $formato = new NumeroALetras();
        $totalString = $formato->toMoney($factura->total,2,'LEMPIRAS','CENTAVOS');
        $pdf = PDF::loadView('pdfs.factura',[
            'empresa' => DatosEmpresa::first(),
            'factura' => $factura,
            'sucursal' => $factura->directriz_impresion->sucursal,
            'caja'     => $factura->historial_caja->caja,
            'user'     => $factura->user,
            'cliente'  => $factura->cliente,
            'cuerpo'   => $factura->cuerpo_facturas,
            'sumItems' => $factura->cuerpo_facturas->sum('cantidad'),
            'totalString' => $totalString,
            'directriz'   => $factura->directriz_impresion
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('FACTURA REST. #'.$factura->contador.'.pdf');
    }

    public function reporteCuentas($user, $cliente, $token){
        if ($cliente == 0):
            $datosCliente = null;
            $facturas = Factura::with(['cliente'])
                ->where('cancelado', false)
                ->get();
        else:
            $datosCliente = Cliente::where('id',$cliente)->first();
            $facturas = Factura::with(['cliente'])
                ->where('cliente_id', $cliente)
                ->where('cancelado', false)
                ->get();
        endif;
        $total = $facturas->sum('total');
        $cobrado = $facturas->sum('cobrado');
        $pdf = PDF::loadView('pdfs.reporte_cuentas',[
            'empresa' => DatosEmpresa::first(),
            'facturas' => $facturas,
            'user'     => $user,
            'cliente'  => $datosCliente,
            'total'    => $total,
            'cobrado'  => $cobrado
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('REPORTE DE FACTURAS PENDIENTES.pdf');
    }
}
