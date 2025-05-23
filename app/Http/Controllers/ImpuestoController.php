<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;
use App\Models\Impuesto;
use Illuminate\Http\Request;

class ImpuestoController extends Controller
{
    public function index(){
        return response()->json(['impuestos'=>Impuesto::all()],200);
    }

    public function indexFormasPagos(){
        return response()->json(['formas'=>FormaPago::all()],200);
    }
}
