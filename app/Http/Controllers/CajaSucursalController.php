<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaSucursalController extends Controller
{
    public function show($caja){
        return response()->json([
            'caja' => Caja::with([
                'sucursal',
                'user'
            ])->where('id', $caja)->first()
        ], 200);
    }
}
