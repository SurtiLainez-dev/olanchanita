<?php

namespace App\Http\Controllers;

use App\Models\FamiliaArticulo;
use App\Models\SubFamiliaArticulo;
use Illuminate\Http\Request;

class FamiliaArticuloController extends Controller
{
    public function store(Request $request){
        $nuevaFamilia = new FamiliaArticulo();
        $nuevaFamilia->nombre = $request->input('nombre');
        $nuevaFamilia->codigo = $this->codigoFamilia();
        $nuevaFamilia->save();
        return response()->json(['status'=>'Ok'], 200);
    }

    public function codigoFamilia(){
        $bandera = true;
        while ($bandera == true){
            $num = rand(10,999);
            if (FamiliaArticulo::where('codigo', $num)->exists()){
                $bandera = true;
            }else{
                $bandera = false;
            }
        }
        return $num;
    }

    public function update(Request $request){
        $modificarFamilia = FamiliaArticulo::find($request->id);
        $modificarFamilia->nombre = $request->input('nombre');
//        if ($request->hasFile('img')):
//            $dir = s3::CargarArchivos($request->img, 'public/web/categorias','public');
//            $modificarFamilia->img = $dir;
//        endif;
        $modificarFamilia->save();
        return response()->json(['status'=>'ok'], 200);
    }

    public function index(){
        $familias = FamiliaArticulo::with(['sub_familia_articulos'])->get();

        return response()->json(['familias'=>$familias], 200);
    }

    public function storeS(Request $request){
        $nuevaSubFamilia = new SubFamiliaArticulo();
        $nuevaSubFamilia->familia_articulo_id = $request->input('familia');
        $nuevaSubFamilia->nombre = $request->input('nombre');
        $familia = FamiliaArticulo::where('id',$request->input('familia'))->first();
        $contador = SubFamiliaArticulo::where('familia_articulo_id', $familia->id)->count();
        $nuevaSubFamilia->codigo = $familia->codigo.$contador;
//        if ($request->hasFile('img')):
//            $dir = s3::CargarArchivos($request->img, 'public/web/categorias','public');
//            $nuevaSubFamilia->img = $dir;
//        endif;
        $nuevaSubFamilia->save();

        return response()->json(['status'=>'ok'], 200);
    }

    public function indexS(){
        $familias = SubFamiliaArticulo::with(['familia_articulo'])->get();
        return response()->json(['Sfamilias'=>$familias], 200);
    }

    public function updateS(Request $request){
        $modificarFamilia = SubFamiliaArticulo::find($request->id);
        $modificarFamilia->nombre = $request->input('nombre');
        $modificarFamilia->familia_articulo_id = $request->input('familia');
//        if ($request->hasFile('img')):
//            $dir = s3::CargarArchivos($request->img, 'public/web/categorias','public');
//            $modificarFamilia->img = $dir;
//        endif;
        $modificarFamilia->save();
        return response()->json(['status'=>'ok'], 200);
    }
}
