<?php

namespace App\Http\Controllers;

use App\Models\FacturaProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaProveedorController extends Controller
{
    public function store(Request $request){
        $nuevaFactura = new FacturaProveedor();
        $nuevaFactura->proveedor_id = $request->proveedor_id;
        $nuevaFactura->referencia   = $request->referencia;
        $nuevaFactura->saldo_inicial = $request->saldo_inicial;
        $nuevaFactura->saldo_actual  = $request->saldo_actual;
        $nuevaFactura->fecha         = $request->fecha;
        if ($request->hasFile('file'))
            S3::cargarFileS3($request->file('file'),'factura/', 'private');
        if ($request->orden_entrada_id > 0):
            $nuevaFactura->orden_entrada_id = $request->orden_entrada_id;
            DB::table('orden_entradas')->where('id', $request->orden_entrada_id)
                ->update(['estado'=>1]);
        endif;
        $nuevaFactura->save();

        return response()->json(['msj'=>'Se ha registrado la factura exitosamente'],200);
    }

    public function index(){
        $facturas = DB::table('factura_proveedors as fp')
            ->join('proveedors as pro','pro.id','=','fp.proveedor_id')
            ->select('pro.nombre','fp.referencia','fp.saldo_inicial','fp.saldo_actual','fp.fecha','fp.id')
            ->get();

        return response()->json(['facturas'=>$facturas],200);
    }

    public function show($id){
        $factura = FacturaProveedor::with([
            'orden_entrada',
            'proveedor'
        ])->where('id', $id)->first();

        return response()->json(['factura'=>$factura],200);
    }
}
