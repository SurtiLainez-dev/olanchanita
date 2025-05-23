<?php

namespace App\Http\Controllers;

use App\Models\DatosEmpresa;
use App\Models\ItemOrdenCompra;
use App\Models\OrdenCompra;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenCompraController extends Controller
{
    public function store(Request  $request){
        $nuevaOrden = new OrdenCompra();
        $nuevaOrden->proveedor_id = $request->proveedor;
        $nuevaOrden->user_id      = Auth::user()->id;
        $nuevaOrden->comentario   = $request->comentario;
        $nuevaOrden->fecha_entrega = $request->fecha;
        $nuevaOrden->total         = $request->total;
        $nuevaOrden->sucursal_id   = 1;
        $nuevaOrden->estado        = 'PENDIENTE';
        $nuevaOrden->cod           = FacturasController::generarContador(OrdenCompra::count()+1,0,'');
        $nuevaOrden->save();

        foreach ($request->items as $item):
            $nuevoItem = new ItemOrdenCompra();
            $nuevoItem->orden_compra_id = $nuevaOrden->id;
            if ($item['id'] && $item['id'] > 0)
                $nuevoItem->articulo_id = $item['id'];
            $nuevoItem->cant            = $item['cant'];
            $nuevoItem->precio          = $item['precio'];
            $nuevoItem->total           = $item['total'];
            $nuevoItem->comentario      = $item['comentario'];
            $nuevoItem->save();
        endforeach;

        return response()->json(['msj'=>'Se ha registrado la orden exitosamente'], 200);
    }

    public function index(){
        $ordenes = DB::table('orden_compras as oc')
            ->join('proveedors as p','p.id','=','oc.proveedor_id')
            ->join('users as u','u.id','=','oc.user_id')
            ->select('oc.estado','oc.fecha_entrega','oc.cod','p.nombre as proveedor','oc.id','oc.created_at')
            ->orderBy('oc.created_at','ASC')
            ->get();

        return response()->json(['ordenes'=>$ordenes],200);
    }

    public function pdf($user, $cod, $token){
        $orden = OrdenCompra::with([
            'proveedor',
            'user',
            'item_orden_compras'=>function($query){
                $query->with([
                    'articulo' => function($query){
                        $query->with(['marca']);
                    }
                ]);
            },
        ])->where('cod', $cod)
            ->first();

        $pdf = PDF::loadView('pdfs.oden_compra',[
            'empresa'  => DatosEmpresa::first(),
            'orden'   => $orden,
        ]);
        $pdf->setPaper('letter');
        return $pdf->stream('ORDEN DE COMPRA '.$orden->cod.'pdf');
    }
}
