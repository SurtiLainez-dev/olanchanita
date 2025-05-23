<?php

namespace App\Http\Controllers;


use App\Exports\InventarioExport;
use App\Models\Articulo;
use App\Models\Combo;
use App\Models\EstadoArticulo;
use App\Models\HistorialArticulo;
use App\Models\MarcaProveedor;
use App\Models\PrecioArticulo;
use App\Models\Proveedor;
use App\Models\StockArticulo;
use App\Models\StockPadre;
use App\Models\SubFamiliaArticulo;
use App\Models\Sucursal;
use App\Models\TipoEntradaArticulo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ArticuloController extends Controller
{

    public function update(Request $request, $id){
        $editArticulo = Articulo::find($id);
        $editArticulo->modelo = $request->modelo;
        $editArticulo->nombre = $request->nombre;
        $editArticulo->descripcion = $request->detalle;
        $editArticulo->codigo_barras = $request->barras;
        $editArticulo->is_contable   = $request->is_contable;
        $editArticulo->save();

        return response()->json(['msj'=>'Se ha editado el articulo exitosamente'],200);
    }
    public function excel($user,$sucursal, $clave){
        $data = DB::table('stock_articulos')
            ->join('articulos','articulos.id','=','stock_articulos.articulo_id')
            ->join('marcas','marcas.id','=','articulos.marca_id')
            ->join('sub_familia_articulos','sub_familia_articulos.id','=','articulos.sub_familia_articulo_id')
            ->join('familia_articulos','familia_articulos.id','=','sub_familia_articulos.familia_articulo_id')
            ->where('sucursal_id', $sucursal)
            ->select('articulos.codigo_sistema as cod','articulos.nombre','articulos.modelo',
                'marcas.nombre as marca','stock_articulos.stock_actual','sub_familia_articulos.nombre as sub',
                'familia_articulos.nombre as fam')
            ->orderBy('marcas.nombre')
            ->get();
        $Sucursal = DB::table('sucursals')->where('id', '=',$sucursal)->first();
        $dataArray = array();
        foreach ($data as $item):
            $datos = array(
                "codigo" => $item->cod,
                "nombre" => $item->nombre,
                "modelo" => $item->modelo,
                "marca"  => $item->marca,
                "Familia" => $item->fam,
                "SubFamilia" => $item->sub,
                "stock"  => $item->stock_actual,
            );

            array_push($dataArray, $datos);
        endforeach;

        return Excel::download(new InventarioExport($dataArray),'reporte inventario '.time().' suc-'.$Sucursal->nombre.'.xlsx');
    }

    public function generarCodigoBarra(Request $request){
        $barras = $this->crearCodigoBarra();
        DB::table('articulos')->where('id', $request->id)->update(['codigo_barras'=>$barras]);
    }
    public function store(Request $request){
        $nuevoArticulo = new Articulo();
        $nuevoArticulo->modelo                  = $request->modelo;
        $nuevoArticulo->nombre                  = $request->nombre;
        $nuevoArticulo->descripcion             = $request->descripcion;
        $nuevoArticulo->sub_familia_articulo_id = $request->subfamilia;
        $nuevoArticulo->codigo_sistema          = self::codigo($request->subfamilia);
        $nuevoArticulo->codigo_barras           = $request->codigo_barras;;
        $nuevoArticulo->marca_id                = $request->marca;
        $nuevoArticulo->precio_costo            = $request->precio;
        $nuevoArticulo->stock_minimo            = $request->sMinimo;
        $nuevoArticulo->stock_maximo            = $request->sMaximo;
        if ($request->isCompuesto == 'false' || $request->isCompuesto == false)
            $nuevoArticulo->is_contable         = 0;
        else
            $nuevoArticulo->is_contable         = 1;
        $nuevoArticulo->is_visible              = true;
        $nuevoArticulo->save();

        self::addHistorialArticulo($nuevoArticulo->id, 'CREACIÓN DE ARTÍCULO EN LA BASE DE DATOS', null);

        return response()->json(['msj'=>'Se ha creado exitasamente el articulo'],200);
    }

    public static function addHistorialArticulo($articulo_id,$detalle,$sucursal){
        $nuevoHistorialArticulo = new HistorialArticulo();
        $nuevoHistorialArticulo->articulo_id = $articulo_id;
        $nuevoHistorialArticulo->detalle     = $detalle;
        $nuevoHistorialArticulo->sucursal_id = $sucursal;
        $nuevoHistorialArticulo->user_id     = Auth::user()->id;
        $nuevoHistorialArticulo->save();
    }

    public function crearCodigoBarra(){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        $bandera          = true;
        while ($bandera):
            for ($i = 0; $i < 11; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            if (Articulo::where('codigo_barras', $randomString)->exists()):
                $bandera = true;
            else:
                $bandera = false;
            endif;
        endwhile;
        return $randomString;
    }

    public static function  codigo($subFamilia){
        $sub = SubFamiliaArticulo::where('id', $subFamilia)->first();
        $cantidad = Articulo::where('sub_familia_articulo_id', $subFamilia)->count();
        if ($cantidad == 0 || $cantidad < 10){
            $cuerpo = '0000'.$cantidad;
        }elseif ($cantidad > 9 || $cantidad < 100){
            $cuerpo = '000'.$cantidad;
        }elseif ($cantidad > 99 || $cantidad < 1000){
            $cuerpo = '00'.$cantidad;
        }elseif ($cantidad > 999 || $cantidad < 10000){
            $cuerpo = '0'.$cantidad;
        }elseif ($cantidad > 9999 || $cantidad < 100000){
            $cuerpo = $cantidad;
        }
        return $sub->codigo.'-'.$cuerpo;
    }

    public function index($tipo, $id){
        if ($tipo == 1){
//            $combos = DB::table('combos')
//                ->select('combos.detalle','combos.id','combos.activo','combos.nombre')
//                ->where('activo', 1)
//                ->get();
            return response()->json(['inventario'=>$this->todo_inventario(),'combos'=>[]], 200);
        }
    }

    public function todo_inventario(){
        $articulos = DB::table('articulos')
            ->join('marcas','marcas.id','=', 'articulos.marca_id')
            ->join('sub_familia_articulos','sub_familia_articulos.id','=','articulos.sub_familia_articulo_id')
            ->join('familia_articulos','familia_articulos.id','=','sub_familia_articulos.familia_articulo_id')
//            ->leftjoin('precio_articulos','precio_articulos.articulo_id','=','articulos.id')
//            ->where('precio_articulos.estado', true)
            ->select('articulos.nombre','articulos.codigo_barras','articulos.codigo_sistema',
                'marcas.nombre as marca','articulos.modelo',
                'sub_familia_articulos.nombre as fam','articulos.id as articulo','articulos.precio_costo',
                'familia_articulos.nombre as familia','articulos.descripcion','articulos.is_contable',
                'stock_minimo as stock_m','stock_maximo as stock_max','articulos.is_visible',
                'marcas.id as marca_id','familia_articulos.id as fam_id','articulos.descripcion',
                'sub_familia_articulos.id as sub_fam_id','articulos.is_contable')
            ->get();
        return $articulos;
    }

    public function show($id){
        $articulo = Articulo::with([
            'marca' => function($query){
                $query->with(['proveedor']);
            },
            'sub_familia_articulo'
        ])->where('id', $id)
            ->first();

        $precios     = PrecioArticulo::with(['impuesto','proveedor'])->where('articulo_id', $id)->get();
        $stock       = StockArticulo::with(['sucursal','articulo'])->where('articulo_id', $id)->get();
        $proveedores = MarcaProveedor::with(['proveedor'])->where('marca_id',$articulo->marca_id)->get();
        $stockPadres = StockPadre::with([
            'articulo',
            'articulo_padre' => function($query){
                $query->with([
                    'marca',
                    'stock_articulos' => function($query){
                        $query->with(['sucursal']);
                    }
                ]);
            }
        ]) ->where('articulo_id',$id)->get();

        $historial = HistorialArticulo::with(['sucursal','user'])->where('articulo_id', $id)->get();

        return response()->json([
            'articulo'=>$articulo,
            'precios'=>$precios,
            'stock'=>$stock,
            'historial' => $historial,
            'proveedores' => $proveedores,
            'stockPadres'=>$stockPadres],200);
    }

    public function cambiarVisibilidad(Request  $request){
        DB::table('articulos')->where('id', $request->id)
            ->update(['is_visible'=>$request->data]);

        return response()->json(['msj'=>'Se ha cambiado la visibilidad en caja correctamente'],200);
    }

    public function storePrecio(Request $request){
        DB::table('precio_articulos')
            ->where([['articulo_id','=',$request->articulo_id],['is_activo','=', true]])
            ->update(['is_activo'=>false]);
        $nuevoPrecioArticulo = new PrecioArticulo();
        $nuevoPrecioArticulo->precio = $request->precio;
        $nuevoPrecioArticulo->precio_costo = $request->costo;
        $nuevoPrecioArticulo->precio_descuento = $request->precio_descuento;
        $nuevoPrecioArticulo->ganancia         = $request->ganancia;
        $nuevoPrecioArticulo->articulo_id      = $request->articulo_id;
        $nuevoPrecioArticulo->impuesto_id      = $request->impuesto_id;
        $nuevoPrecioArticulo->proveedor_id     = $request->proveedor;
        $nuevoPrecioArticulo->comentario       = $request->comentario;
        $nuevoPrecioArticulo->save();

        $precios = PrecioArticulo::with(['impuesto','proveedor'])->where('articulo_id', $request->articulo_id)->get();
        return response()->json(['msj'=>'Se ha registrado el precio exitosamente','precios'=>$precios],200);
    }

    public function tipoEntradas(){
        return response()->json(['tipos'=>TipoEntradaArticulo::all()], 200);
    }

    public function estados_articulos(){
        return response()->json(['estados'=>EstadoArticulo::all()],200);
    }

    public function inventarioProveedor($proveedor){
        $inventario = Proveedor::where('id', $proveedor)->with([
            'marca_proveedors' => function($query){
                $query->with([
                    'marca' => function($query){
                        $query->with([
                            'articulos' => function($query){
                                $query->with(['marca','sub_familia_articulo']);
                            }
                        ]);
                    }
                ]);
            }
        ])->first();

        return response()->json(['inventario'=>$inventario], 200);
    }

    public function inventarioSucursal($sucursal){
        $inventario = StockArticulo::with([
            'articulo' => function($query){
                $query->with([
                    'marca',
                    'sub_familia_articulo' => function($query){
                        $query->with(['familia_articulo']);
                    }
                ]);
            }
        ])->where('sucursal_id', $sucursal)
            ->get();

        return response()->json(['inventario'=>$inventario], 200);
    }

    public function articulos(){
        $articulos = Articulo::with([
            'precio_articulos' => function($query){
                $query->where('is_activo',1);
            },
            'sub_familia_articulo'
        ])->get();

//        $combos = DB::table('combos')
//            ->select('combos.detalle','combos.id','combos.activo','combos.nombre','combos.precio','combos.articulos','combos.cant_permitida_x_niveles')
//            ->where('activo', 1)
//            ->get();
        $combos = Combo::with([
            'cuerpo_combos' => function($query){
                $query->with([
                    'articulo' => function($query){
                        $query->with(['sub_familia_articulo']);
                    }
                ]);
            }
        ])->where('activo',1)
            ->get();
//        foreach ($combos as $combo):
//            $ids = json_decode($combo->articulos);
//            $combo->arts = [];
//            for ($i = 0; $i < count($ids); $i++):
//                $data = Articulo::with(['sub_familia_articulo'])->where('id',$ids[$i])->first();
//                array_push($combo->arts, $data);
//            endfor;
//        endforeach;

        return response()->json(['inventario'=>$articulos,'combos'=>$combos],200);
    }
}
