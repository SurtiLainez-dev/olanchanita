<?php

namespace App\Http\Controllers;

use App\Models\MarcaProveedor;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function proveedorXmarca($marca){
        return response()->json(['proveedores'=>MarcaProveedor::with(['proveedor'])->where('marca_id',$marca)->get()],200);
    }
    public function store(Request  $request){
//        $isFoto = false;
//        if ($request->file('logo')):
//            $isFoto = true;
//        endif;
        $newProveedor = new \App\Models\Proveedor();
        $newProveedor->nombre        = $request->input('nombre');
        $newProveedor->codigo_postal = $request->input('codigo_postal');
        $newProveedor->direccion     = $request->input('direccion');
        $newProveedor->email         = $request->input('email');
        $newProveedor->telefono      = $request->input('telefono');
//        $newProveedor->swift         = $request->input('swift');
//        if ($isFoto == true){
//            $newProveedor->logo      = s3::CargarArchivos($request->file('logo'), 'proveedores/logos','public');
//        }
        $newProveedor->save();
        return response()->json(['status'=>'Ok'], 200);
    }

    public function index(){
        return response()->json(['proveedores'=>\App\Models\Proveedor::get()],200);
    }

    public function show($id){
        $proveedor = Proveedor::where('id', $id)->first();
//        $proveedor->contacto_proveedors;
        return response()->json(['proveedor'=>$proveedor], 200);
    }
}
