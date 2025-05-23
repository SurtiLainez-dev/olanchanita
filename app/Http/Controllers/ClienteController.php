<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function store(Request $request){
        $nuevoCliente = new Cliente();
        $nuevoCliente->nombre    = $request->nombre;
        $nuevoCliente->rtn       = $request->rtn;
        $nuevoCliente->telefono  = $request->telefono;
        $nuevoCliente->direccion = $request->direccion;
        $nuevoCliente->save();

        return response()->json(['msj'=>'Se ha registrado el cliente correctamente'],200);
    }

    public function index(){
        return response()->json(['clientes'=>Cliente::all()],200);
    }
}
