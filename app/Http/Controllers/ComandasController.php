<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Comanda;
use App\Models\CuerpoComanda;
use App\Models\DatosEmpresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;

class ComandasController extends Controller
{
    public function store(Request $request){
        $nuevaComanda             = new Comanda();
        $nuevaComanda->nombre     = $request->nombre;
        $nuevaComanda->total      = $request->total;
        $nuevaComanda->user_id    = Auth::user()->id;
        $nuevaComanda->mesa_id    = $request->mesa_id;
        $nuevaComanda->estado     = false;
        $nuevaComanda->comentario = $request->comentario;
        $nuevaComanda->saldo_actual = $request->total;
        $nuevaComanda->save();

        $this->storeCuerpoComanda($request, $nuevaComanda->id);

        return response()->json(['msj'=>'Se ha registrado la comanda exitosamente','comanda'=>$nuevaComanda->id],200);
    }

    public function storeCuerpoComanda($request, $comanda_id){
        foreach ($request->items as $item):
            $nuevoRegistroCombo = new CuerpoComanda();
            if ($item['combo'] != null):
                $nuevoRegistroCombo->combo_id = $item['id'];
                $nuevoRegistroCombo->is_combo = 1;
            else:
                $nuevoRegistroCombo->articulo_id = $item['id'];
                $nuevoRegistroCombo->is_combo    = 0;
            endif;
            $nuevoRegistroCombo->comanda_id     = $comanda_id;
            $nuevoRegistroCombo->cant           = $item['cantidad'];
            $nuevoRegistroCombo->total          = $item['total'];
            $nuevoRegistroCombo->imprimible     = $item['imprimible'];
            $nuevoRegistroCombo->cant_pendiente = $item['cantidad'];
            $nuevoRegistroCombo->precio_sides   = $item['precio_sides'];
            $nuevoRegistroCombo->save();
        endforeach;
    }

    public function imprimirComanda($usuario,$comanda,$clave){
        $comanda = Comanda::with([
            'cuerpo_comandas' =>function($query){
                $query->with([
                    'combo' => function($query){
                        $query->with([
                            'cuerpo_combos' => function($query){
                                $query->with(['articulo']);
                            }
                        ]);
                    },
                    'articulo'
                ])->where('imprimible', true);
            },
            'mesa'
            ])
            ->where('id', $comanda)
            ->first();

        $pdf = PDF::loadView('pdfs.comanda',[
            'empresa' => DatosEmpresa::first(),
            'comanda' => $comanda,
            'usuario' => User::with(['colaborador'])->where('usuario', $usuario)->first(),
            'mesa'    => $comanda->mesa,
            'platos'  => 0
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('comanda #'.$comanda->id.'.pdf');
    }

    public function index($fecha, $all){
        $condicion = '=';
        if ($all)
            $condicion = '<=';
        $comandas = DB::table('comandas')
            ->join('mesas','mesas.id','=','comandas.mesa_id')
            ->select('comandas.nombre','comandas.total','comandas.estado',
                'mesas.nombre as mesa','comandas.id','comandas.mesa_id','comandas.created_at','comandas.saldo_actual')
            ->whereDate('comandas.created_at',$condicion,$fecha)
            ->orderBy('comandas.created_at','DESC')
            ->get();
        return response()->json(['comandas'=>$comandas],200);
    }

    public function show($id){
        $comanda = Comanda::with([
            'cuerpo_comandas' => function($query){
                $query->with([
                    'articulo' => function($query){
                        $query->with(['sub_familia_articulo']);
                    },
                    'combo' => function($query){
                        $query->with([
                            'cuerpo_combos' => function($query){
                                $query->with([
                                    'articulo' => function($query){
                                        $query->with(['sub_familia_articulo']);
                                    },
                                ]);
                            }
                        ]);
                    }
                ]);
            },
        ])->where('id', $id)->first();

        return response()->json(['comanda'=>$comanda],200);
    }

    public function update(Request $request, $id){
        $editComanda = Comanda::find($id);
        $editComanda->comentario = $request->comentario;
        $editComanda->total      = $request->total;
        $editComanda->saldo_actual = $request->total;
        $editComanda->save();
        DB::table('cuerpo_comandas')->where('comanda_id', $id)->delete();
        $this->storeCuerpoComanda($request, $id);

        return response()->json(['msj'=>'Se ha registrado la comanda exitosamente','comanda'=>$id],200);
    }
}

