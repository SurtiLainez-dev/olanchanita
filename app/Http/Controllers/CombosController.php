<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\CuerpoCombo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CombosController extends Controller
{
    public function store(Request $request){
        $articulos = json_decode($request->articulos);
        $nuevoCombo              = new Combo();
        $nuevoCombo->articulos   = $request->articulos;
        $nuevoCombo->precio      = $request->precio;
        $nuevoCombo->detalle     = $request->detalle;
        $nuevoCombo->nombre      = $request->nombre;
        $nuevoCombo->cant_permitida_x_niveles = null;
        $nuevoCombo->save();

        for ($i = 0; $i < count($articulos); $i++):
            $nuevoRegistroCombo = new CuerpoCombo();
            $nuevoRegistroCombo->articulo_id = $articulos[$i];
            $nuevoRegistroCombo->combo_id    = $nuevoCombo->id;
            $nuevoRegistroCombo->save();
        endfor;

        return response()->json(['msj'=>'Se ha registrado exitosamente el combo'],200);
    }

    public function storeSide(Request $request){
        $articulos                            = $request->articulos;
        $nuevoCombo                           = new Combo();
        $nuevoCombo->precio                   = $request->precio;
        $nuevoCombo->detalle                  = $request->detalle;
        $nuevoCombo->nombre                   = $request->nombre;
        $nuevoCombo->is_sides                 = true;
        $nuevoCombo->cant_niveles_sides       = $request->niveles;
        $nuevoCombo->cant_permitida_x_niveles = $request->detalle_niveles;
        $nuevoCombo->articulos                = $request->detalle_niveles;
        $nuevoCombo->save();

        foreach ($articulos as $articulo):
            $this->newCuerpoCombo($articulo['id'], $nuevoCombo->id,$articulo['nivel'],$articulo['precio'],$articulo['precio_extra'],$articulo['default']);
        endforeach;

        return response()->json(['msj'=>'Se ha registrado el combo extisamente'],200);
    }

    public function index(){
        $combos = Combo::with([
            'cuerpo_combos' => function($query){
                $query->with([
                    'articulo' => function($query){
                        $query->with(['sub_familia_articulo']);
                    }
                ]);
            }
        ])->get();
        return response()->json(['combos'=>$combos],200);
    }

    public function update(Request $request,  $id){
        $articulos                           = $request->articulos;
        $editCombo = Combo::find($id);
        $editCombo->nombre                   = $request->nombre;
        $editCombo->precio                   = $request->precio;
        $editCombo->detalle                  = $request->detalle;
        $editCombo->cant_niveles_sides       = $request->niveles;
        $editCombo->cant_permitida_x_niveles = $request->detalle_niveles;
        $editCombo->save();

        DB::table('cuerpo_combos')->where('combo_id', $id)
            ->update(['activo'=>false]);
        foreach ($articulos as $articulo):
            if ($articulo['r_id']):
                $editRegistroCuerpoCombo                = CuerpoCombo::find($articulo['r_id']);
                $editRegistroCuerpoCombo->nivel         = $articulo['nivel'];
                $editRegistroCuerpoCombo->precio_add    = $articulo['precio'];
                $editRegistroCuerpoCombo->precio_extra  = $articulo['precio_extra'];
                $editRegistroCuerpoCombo->activo        = $articulo['activo'];
                $editRegistroCuerpoCombo->default_nivel = $articulo['default'];
                $editRegistroCuerpoCombo->save();
            else:
                $this->newCuerpoCombo($articulo['id'], $id,$articulo['nivel'],$articulo['precio'],$articulo['precio_extra'],$articulo['default']);
            endif;
        endforeach;

        return response()->json(['msj'=>'Se ha registrado el combo extisamente'],200);
    }

    private function newCuerpoCombo($articulo_id, $combo_id,$nivel,$precio,$precio_e, $default){
        $nuevoRegistroCombo                = new CuerpoCombo();
        $nuevoRegistroCombo->articulo_id   = $articulo_id;
        $nuevoRegistroCombo->combo_id      = $combo_id;
        $nuevoRegistroCombo->nivel         = $nivel;
        $nuevoRegistroCombo->precio_add    = $precio;
        $nuevoRegistroCombo->precio_extra  = $precio_e;
        $nuevoRegistroCombo->activo        = true;
        $nuevoRegistroCombo->default_nivel = $default;
        $nuevoRegistroCombo->save();
    }
}
