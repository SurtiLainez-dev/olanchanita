<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\MarcaProveedor;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::with(['marca_proveedor'=>function($query){$query->with(['proveedor']);}])->get();
        return response()->json(['marcas'=>$marcas], 200);
    }

    public function store(Request $request)
    {
        $nuevaMarca = new Marca();
        $nuevaMarca->nombre       = $request->input('nombre');
//        $nuevaMarca->proveedor_id = $request->input('proveedor');
        $nuevaMarca->save();
        return response()->json(['status'=>'Ok'], 200);
    }

    public function update(Request $request, $id)
    {
        $modificarMarca = Marca::find($id);
        $modificarMarca->nombre       = $request->input('nombre');
        $modificarMarca->save();
        return response()->json(['status'=>'ok'], 200);
    }

    public function storeNuevoProveedor(Request $request){
        if (MarcaProveedor::where([['marca_id','=', $request->marca],['proveedor_id','=', $request->proveedor]])->count() == 0){
            $nuevoProveedorMarca = new MarcaProveedor();
            $nuevoProveedorMarca->marca_id = $request->marca;
            $nuevoProveedorMarca->proveedor_id = $request->proveedor;
            $nuevoProveedorMarca->save();
        }

        return response()->json(['msj'=>'Se ha registrado exitosamente el proveedor en la marca'],200);
    }
}
