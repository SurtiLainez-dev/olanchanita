<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index(){
        $sucursales = Sucursal::select('sucursals.nombre','sucursals.abreviatura','sucursals.email','sucursals.telefono',
                'sucursals.direccion_completa as dir', 'sucursals.id')
            ->get();

        return response()->json(['suc'=>$sucursales], 200);
    }

    public function usuarios($suc){
        $users = Sucursal::where('id', $suc)->with(['users'])->get();
        return \response()->json(['users'=>$users], 200);
    }
}
