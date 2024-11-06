<?php

namespace App\Http\Controllers;



use App\Models\Banco;

use App\Models\CuentaBanco;
use App\Models\TipoCuentaBanco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function store(Request $request){
        $nuevoBanco = new Banco();
        $nuevoBanco->nombre = $request->nombre;
        $nuevoBanco->save();

        return response()->json(['msj'=>'Se ha creado el banco exitosamente'],200);
    }

    public function index(){
        return response()->json(['bancos'=> \App\Models\Banco::all()], 200);
    }

    public function indexCuentas(){
        $cuentas     = CuentaBanco::all();

        foreach ($cuentas as $cuenta){
            $cuenta->banco       = Banco::where('id', $cuenta->banco_id)->first();
            $cuenta->tipo        = TipoCuentaBanco::where('id', $cuenta->tipo_cuenta_banco_id)->first();
        }
        return response()->json(['cuentas'=>$cuentas], 200);
    }

    public function indexTipo(){
        $tipos = TipoCuentaBanco::all();
        return response()->json(['tipos'=>$tipos], 200);
    }

    public function storeCuentaBanco(Request $request){
        $nuevaCuenta = new CuentaBanco();
        $nuevaCuenta->descripcion          = $request->descripcion;
        $nuevaCuenta->tipo_cuenta_banco_id = $request->input('tipo');
        $nuevaCuenta->num                  = $request->input('num');
        $nuevaCuenta->banco_id             = $request->input('banco');
        $nuevaCuenta->total                = 0.00;
        $nuevaCuenta->save();

        return response()->json(['msj'=>'Se ha creado exitosamente la cuenta'],200);
    }
}
