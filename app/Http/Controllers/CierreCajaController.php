<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Models\DatosEmpresa;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CierreCajaController extends Controller
{
    public function store(Request $request){
        $nuevoCierre = new CierreCaja();
        $nuevoCierre->caja_id             = $request->caja;
        $nuevoCierre->resumen_forma_pagos = $request->resumen_pagos;
        $nuevoCierre->total               = $request->total_efectivo;
        $nuevoCierre->total_efectivo      = $request->total;
        $nuevoCierre->egresos             = $request->retiradas;
        $nuevoCierre->efectivo_final_dia  = $request->efectivo_final_dia;
        $nuevoCierre->efectivo_declarado  = $request->efectivo_declarado;
        $nuevoCierre->descuadre           = $request->descuadre;
        $nuevoCierre->fecha               = date('Y-m-d');
        $nuevoCierre->total_tarjeta       = $request->tarjeta;
        $nuevoCierre->save();

        FacturasController::editCaja($request->caja,$request->efectivo_declarado,6,$request->comentario,4,null,null,1,null);
        DB::table('cajas')->where('id',$request->caja)->update(['total'=>0]);

        return response()->json(['msj'=>'Se ha creado el cierre exitosamente'],200);
    }

    public function imprimirCierre($user, $fecha, $token, $caja){
        $cierre = CierreCaja::with([
            'caja' => function($query) use ($fecha){
                $query->with([
                    'user' => function($query){
                        $query->with(['colaborador']);
                    },
                    'historial_cajas' => function($query) use ($fecha){
                        $query->whereDate('created_at',$fecha)->with([
                            'factura' => function($query){
                                $query->with([
                                    'cuerpo_facturas' => function($query){
                                        $query->with([
                                            'articulo' => function($query){
                                                $query->with(['sub_familia_articulo']);
                                            },
                                            'combo' => function($query){
                                                $query->with([
                                                    'cuerpo_combos' => function($query){
                                                        $query->with([
                                                            'articulo' => function($query){
                                                                $query->with(['sub_familia_articulo']);
                                                            },
                                                        ]);
                                                    }
                                                ]);
                                            }
                                        ]);
                                    }
                                ]);
                            },
                            'recibo'
                        ]);
                    }
                ]);
            }
        ])->whereDate('fecha',$fecha)->where('caja_id', $caja)
            ->first();
        $historial = $cierre->caja->historial_cajas;
        $cuerpoFacturas = [];
        $idsArt         = [];
        $idsCombos      = [];
        foreach ($historial as $cuerpo):
            if ($cuerpo->factura):
                foreach ($cuerpo->factura->cuerpo_facturas as $item):
                    if ($item->articulo_id && $item->cantidad > 0):
                        array_push($idsArt, $item->id);
                        array_push($cuerpoFacturas,array(
                           "id" => $item->articulo_id,
                            "nombre" => $item->articulo->nombre,
                            "cantidad"   => $item->cantidad,
                        ));
                    elseif ($item->combo_id):
                        array_push($idsCombos, $item->id);
                        foreach ($item->combo->cuerpo_combos as $combo):
                            array_push($cuerpoFacturas, array(
                               "id" => $combo->articulo_id,
                                "nombre" => $combo->articulo->nombre,
                                "cantidad"   => $item->cantidad,
                            ));
                        endforeach;
                    endif;
                endforeach;
            endif;
        endforeach;
        $resumenVentas = DB::table('cuerpo_facturas as c_f')
            ->whereIn('c_f.id', $idsArt)
            ->join('articulos as art','art.id','=','c_f.articulo_id')
            ->join('sub_familia_articulos as sub','sub.id','=','art.sub_familia_articulo_id')
            ->distinct('sub.id')
            ->select('sub.nombre',DB::raw('sum(c_f.cantidad) as cant'),DB::raw('sum(c_f.total) as total'),'c_f.precio')
            ->groupBy('sub.nombre','c_f.precio')
            ->get();
        $resumenVentas2 = DB::table('cuerpo_facturas as c_f')
            ->whereIn('c_f.id', $idsCombos)
            ->join('combos as com','com.id','=','c_f.combo_id')
            ->select('com.nombre',DB::raw('sum(c_f.total) as total'),DB::raw('sum(c_f.cantidad) as cant'))
            ->groupBy('com.nombre')
            ->get();
        $cuerpoFacturas = collect($cuerpoFacturas);
        $resumen3       = collect();
        foreach ($cuerpoFacturas as $item){
            if ($resumen3->where('id', $item['id'])->count() === 0){
                $resumen3->add(array(
                    "id" => $item['id'],
                    "nombre" => $item['nombre'],
                    "cant"   => $cuerpoFacturas->where('id', $item['id'])->sum('cantidad')
                ));
            }
        }

        $pdf = PDF::loadView('pdfs.cierre',[
            'fecha'   => $fecha,
            'empresa' => DatosEmpresa::first(),
            'usuario' => $user,
            'colaborador' => $cierre->caja->user->colaborador,
            'cierre'      => $cierre,
            'forma_pagos' => json_decode($cierre->resumen_forma_pagos),
            'historial'   => $cierre->caja->historial_cajas,
            'resumen1'    => $resumenVentas,
            'resumen2'    => $resumenVentas2,
            'resumen3'    => $resumen3
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('Cierre de caja - '.$fecha.'.pdf');
//        dd($resumen3) ;
    }

    public function consultaFecha(){
        $fechas = DB::table('historial_cajas')
//            ->groupBy(DB::raw('DATE(created_at)'))
            ->distinct(DB::raw('DATE(created_at)'))
            ->select(DB::raw('DATE(created_at) as fecha'))
            ->get();

        return response()->json(['fechas'=>$fechas],200);
    }

    public function index($caja, $fecha){
        $cierre = CierreCaja::with(['caja'])
            ->where('caja_id', $caja)
            ->whereDate('fecha', $fecha)
            ->first();

        return response()->json(['cierre'=>$cierre],200);
    }
}
