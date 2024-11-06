<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\CuerpoCuenta;
use App\Models\DatosEmpresa;
use App\Models\DirectrizImpresion;
use App\Models\Factura;
use App\Models\HistorialCaja;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Luecano\NumeroALetras\NumeroALetras;

class ReciboController extends Controller
{
    public function store(Request $request){
        if ($request->tipo == 0)
            $tipo = 3;
        elseif ($request->tipo == 1 || $request->tipo == 2)
            $tipo = 2;

        if (DirectrizImpresion::where('sucursal_id', $request->sucursal_is)->where('tipo', 1)->where('estado', 1)->count() == 1):
            $directriz    = FacturasController::directrizImpresion($request->sucursal_is, 1);
            $nuevoRecibo  = new Recibo();
            $nuevoRecibo->user_id = Auth::user()->id;
            $nuevoRecibo->caja_id = $request->caja_id;
            $nuevoRecibo->total   = $request->total;
            $nuevoRecibo->comentario = $request->comentario;
            if ($request->factura)
                $nuevoRecibo->factura_id = $request->factura;
            if ($request->cuenta)
                $nuevoRecibo->cuenta_id  = $request->cuenta;
            $nuevoRecibo->contador = FacturasController::generarContador($directriz->contador_actual, $directriz->inicio_contador, $directriz->codigo_post_contador);
            $nuevoRecibo->save();

            FacturasController::editCaja($request->caja_id,$request->total,$request->forma_pago_id,$nuevoRecibo->contador,$tipo,null,$nuevoRecibo->id,0, null);
            FacturasController::sumarContadorFactura($directriz->id, $directriz->contador_actual);

            if ($request->factura):
                $this->editarFactura($request->factura, $request->total);
            endif;

            if ($request->cuenta && $request->tipo == 2):
                $editCuenta = Cuenta::find($request->cuenta);
                $editCuenta->saldo_actual = $editCuenta->saldo_actual - $request->total;
                if ($editCuenta->saldo_actual <= 0)
                    $editCuenta->estado = true;
                $editCuenta->save();
                $totalPendiente = $request->total;
                $cuerpoCuenta = CuerpoCuenta::with(['factura'])->where('cuenta_id', $request->cuenta)->get();
                foreach ($cuerpoCuenta as $factura):
                    if ($totalPendiente > 0):
                        if ($factura->factura->cancelado == 0):
                            $saldoPentienteFactura = $factura->factura->total - $factura->factura->cobrado;
                            if ($saldoPentienteFactura > 0):
                                if ($totalPendiente >= $saldoPentienteFactura):
                                    $this->editarFactura($factura->factura_id, $saldoPentienteFactura);
                                    $totalPendiente = $totalPendiente - $saldoPentienteFactura;
                                elseif ($totalPendiente < $saldoPentienteFactura):
                                    $this->editarFactura($factura->factura_id, $totalPendiente);
                                    $totalPendiente = 0;
                                endif;
                            endif;
                        endif;
                    else:
                        break;
                    endif;
                endforeach;
            endif;

            return  response()->json(['msj'=>'Recibo registrado','contador'=>$nuevoRecibo->contador],200);
        else:
            return response()->json(['msj'=>'No hay directriz de impresión'],422);
        endif;
    }

    public function editarFactura($id, $total){
        $editFactura = Factura::find($id);
        $editFactura->cobrado = $editFactura->cobrado + $total;
        if ($editFactura->cobrado >= $editFactura->total)
            $editFactura->cancelado = 1;
        $editFactura->save();
    }

    public function printRecibo($user, $contador, $token){
        $recibo = HistorialCaja::with([
            'recibo' => function($query){
                $query->with([
                    'factura' => function($query){
                        $query->with(['cliente']);
                    },
                    'caja' => function($query){
                        $query->with(['sucursal']);
                    },
                    'user' => function($query){
                        $query->with(['colaborador']);
                    },
                    'cuenta' => function($query){
                        $query->with([
                            'cuerpo_cuentas' => function($query){
                                $query->with([
                                    'factura' => function($query){
                                        $query->with(['cliente']);
                                    },
                                ]);
                            }
                        ]);
                    }
                ]);
            },
            'forma_pago'
        ])->whereHas('recibo',function (Builder $query) use ($contador){
            $query->where('contador', $contador);
        })->first();
        $formato = new NumeroALetras();
        $totalString = $formato->toMoney($recibo->recibo->total,2,'LEMPIRAS','CENTAVOS');
        $pdf = PDF::loadView('pdfs.recibo',[
            'empresa' => DatosEmpresa::first(),
            'recibo' => $recibo->recibo,
            'colaborador' => $recibo->recibo->user->colaborador,
            'sucursal'    => $recibo->recibo->caja->sucursal,
            'user'        => $recibo->recibo->user,
            'caja'        => $recibo->recibo->caja,
            'forma_pago'  => $recibo->forma_pago,
            'totalString' => $totalString
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('RECIBO #'.$recibo->recibo->contador.'.pdf');
    }
}
