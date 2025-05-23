<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Colaborador;
use App\Models\CuentaBancoColaborador;
use App\Models\PuestoColaborador;
use App\Models\User;
use Illuminate\Http\Request;

class ColaboradoresController extends Controller
{
    public function index(){
        $colaboradores = Colaborador::join('sucursals','sucursals.id','=','colaboradors.sucursal_id')
            ->join('puesto_colaboradors','puesto_colaboradors.id','=','colaboradors.puesto_colaborador_id')
            ->select('puesto_colaboradors.nombre as puesto','sucursals.nombre as sucursal','colaboradors.nombres',
                'colaboradors.apellidos','colaboradors.identidad','colaboradors.email','colaboradors.telefono',
                'colaboradors.estado','colaboradors.id as col')->get();

        return response()->json(['colaboradores'=>$colaboradores], 200);
    }

    public function puestos(){
        $puestos = PuestoColaborador::all();
        return response()->json(['puestos'=>$puestos], 200);
    }

    public function storePuestoColaborador(Request $request){
        $nuevoPuesto = new PuestoColaborador();
        $nuevoPuesto->nombre = $request->input('nombre');
        $nuevoPuesto->save();
        return response()->json(['status'=>'OK'], 200);
    }

    public function store(Request $request){
        $nuevoColaborador = new Colaborador();
        $nuevoColaborador->nombres               = $request->input('nombres');
        $nuevoColaborador->apellidos             = $request->input('apellidos');
        $nuevoColaborador->email                 = $request->input('email');
        $nuevoColaborador->telefono              = $request->input('telefono');
        $nuevoColaborador->sucursal_id           = $request->input('sucursal');
        $nuevoColaborador->puesto_colaborador_id = $request->input('puesto');
        $nuevoColaborador->estado                = true;
        $nuevoColaborador->identidad             = $request->input('identidad');
        $nuevoColaborador->save();
        $this->crearCuentaBanco($nuevoColaborador->id, $request->input('banco'),$request->input('numBanco'));

        return response()->json(['msj'=>'Se ha registrado exitosamente el colaborador'],200);
    }

    public function crearCuentaBanco($col, $banco, $num){
        $nuevaCuenta = new CuentaBancoColaborador();
        $nuevaCuenta->colaborador_id = $col;
        $nuevaCuenta->banco_id       = $banco;
        $nuevaCuenta->cuenta         = $num;
        $nuevaCuenta->save();
    }

    public function show($colaborador){
        $colaborador = Colaborador::with([
            'sucursal',
            'puesto_colaborador',
            'contratos',
            'user',
            'cuentas_banco_colaboradors' => function($query){
                $query->with(['banco']);
            }
        ])->where('id', $colaborador)->first();
        $isUser = User::where('colaborador_id','=', $colaborador)->count();
        return \response()->json(['col'=>$colaborador,'isUser'=>$isUser], 200);
    }

    public function colaboradoresXsucursal($suc){
        $colaboradores = Colaborador::join('sucursals','sucursals.id','=','colaboradors.sucursal_id')
            ->join('puesto_colaboradors','puesto_colaboradors.id','=','colaboradors.puesto_colaborador_id')
            ->where('sucursals.id', $suc)
            ->where('colaboradors.estado', true)
            ->select('colaboradors.id','colaboradors.nombres','colaboradors.apellidos','colaboradors.identidad',
                'sucursals.nombre as sucursal','puesto_colaboradors.nombre as puesto')
            ->get();
        return response()->json(['col'=>$colaboradores], 200);
    }
}
