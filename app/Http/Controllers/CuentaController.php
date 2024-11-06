<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\CuerpoCuenta;
use App\Models\CuerpoFactura;
use App\Models\DatosEmpresa;
use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaController extends Controller
{
    public function store(Request $request){
        $nuevaCuenta = new Cuenta();
        $nuevaCuenta->nombre = $request->nombre;
        $nuevaCuenta->contador = $this->crearContador(Cuenta::count());
        $nuevaCuenta->saldo_inicial = $request->total;
        $nuevaCuenta->saldo_actual  = $request->total;
        $nuevaCuenta->estado        = false;
        $nuevaCuenta->user_id       = Auth::user()->id;
        $nuevaCuenta->save();

        foreach ($request->cuerpo as $item):
            $nuevoRegistroCuenta = new CuerpoCuenta();
            $nuevoRegistroCuenta->cuenta_id = $nuevaCuenta->id;
            $nuevoRegistroCuenta->factura_id = $item;
            $nuevoRegistroCuenta->save();
        endforeach;

        return response()->json(['msj'=>'se ha registrado la cuenta exitosamente'],200);
    }

    public function cuentasPendientes(){
        $cuentas = Cuenta::with([
            'cuerpo_cuentas' => function($query){
                $query->with([
                    'factura' => function($query){
                        $query->with(['cliente']);
                    }
                ]);
            }
        ])->where('estado', false)->get();

        $facturas = Factura::with([
            'cliente'
        ])->where('tipo', false)
            ->where('cancelado', false)
            ->get();


        return response()->json(['cuentas'=>$cuentas, 'facturas'=>$facturas],200);
    }

    public function crearContador($idActual){
        if ($idActual < 10)
            return '000000000'.$idActual;
        elseif ($idActual >= 10 && $idActual < 100)
            return '00000000'.$idActual;
        elseif ($idActual >= 100 && $idActual < 1000)
            return '0000000'.$idActual;
        elseif ($idActual >= 1000 && $idActual < 10000)
            return '000000'.$idActual;
        elseif ($idActual >= 10000 && $idActual < 100000)
            return '00000'.$idActual;
        elseif ($idActual >= 100000 && $idActual < 1000000)
            return '0000'.$idActual;
        elseif ($idActual >= 1000000 && $idActual < 10000000)
            return '000'.$idActual;
    }

    public function imprimirCuenta($user, $contador, $token){
        $cuenta = Cuenta::with([
            'cuerpo_cuentas' => function($query){
                $query->with([
                    'factura' => function($query){
                        $query->with(['cliente']);
                    }
                ]);
            }
        ])->where('contador', $contador)
            ->first();

        $pdf = PDF::loadView('pdfs.cuenta',[
            'empresa'  => DatosEmpresa::first(),
            'cuenta'   => $cuenta,
            'facturas' => $cuenta->cuerpo_cuentas,
            'user'     => $user
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('REPORTE DE CUENTA '.$contador.'pdf');
    }
}
