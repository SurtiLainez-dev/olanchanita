<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CierreCaja;
use App\Models\DatosEmpresa;
use App\Models\HistorialCaja;
use App\Models\RetiradaEfectivo;
use AWS\CRT\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;

class CajaController extends Controller
{
    public function registrarEgreso(Request $request){
        $retirada_efectivo = null;

        $nuevaRetirada = new RetiradaEfectivo();
        if ($request->tipo_salida == 0)
            $nuevaRetirada->cuenta_banco_id = $request->cuenta_banco;
        $nuevaRetirada->total = $request->total;
        $nuevaRetirada->user_id = Auth::user()->id;
        $nuevaRetirada->comentario = $request->comentario;
        if ($request->tipo_salida == 0):
            $nuevaRetirada->tipo_salida = 'POR DEPOSITO';
        elseif ($request->tipo_salida == 1):
            $nuevaRetirada->tipo_salida = 'RETIRADA NORMAL';
        elseif ($request->tipo_salida == 2):
            $nuevaRetirada->tipo_salida = 'GASTO';
            $gasto = GastoController::storeGasto($request->tipo_gasto, Auth::user()->id,$request->caja, null, $request->comentario, $request->total);
            if ($request->hasFile('file')):
                DB::table('gastos')
                    ->where('id', $gasto)
                    ->update([
                        'file' => S3::cargarFileS3($request->file('file'),'gastos/', 'private')
                    ]);
            endif;
        endif;
        $nuevaRetirada->save();

        FacturasController::editCaja(
            $request->caja,
            $request->total,
            6,
            $request->comentario,
            4,
            null,
            null,
            1,
            $nuevaRetirada->id);
        return response()->json(['msj'=>'Se ha creado exitosamente el egreso. Tienes que cargar el documento firmado'],200);
    }

    public function index(){
        $cajas = Caja::join('sucursals','sucursals.id','=','cajas.sucursal_id')
            ->join('users','users.id','=','cajas.user_id')
            ->select('sucursals.nombre','users.usuario','cajas.num_caja','cajas.codigo','cajas.id as caja',
                'sucursals.id as suc','cajas.total')
            ->get();

        return response()->json(['cajas'=>$cajas], 200);
    }

    public function store(Request $request){
        if ($request->tipo === 1):
            $contador               = Caja::count();
            $nuevaCaja = new Caja();
            $nuevaCaja->sucursal_id = $request->sucursal;
            $nuevaCaja->user_id     = $request->user_id;
            $nuevaCaja->codigo      = '00000'.$contador;
            $nuevaCaja->num_caja    = $contador+1;
            $nuevaCaja->save();
        else:
            DB::table('cajas')->where('id', $request->input('caja'))
                ->update([
                    'user_id'=>$request->input('user_id')
                ]);
        endif;

        return response()->json(['msj'=>'Se ha realizado la accion exitosamente'],200);
    }

    public function show($caja){
        $caja = Caja::join('sucursals','sucursals.id','=','cajas.sucursal_id')
            ->join('users','users.id','=','cajas.user_id')
            ->select('sucursals.nombre','cajas.codigo','users.usuario','cajas.num_caja')
            ->where('cajas.id',$caja)->get();
        return response()->json(['caja'=>$caja], 200);
    }

    public function cajas(){
        return response()->json([
            'cajas' => Caja::with(['sucursal','user'])->get()
        ],200);
    }

    public function storeConfiguracion(Request $request){
        $updateConfiguracion = Caja::find($request->id);
        $updateConfiguracion->password      = bcrypt($request->password);
        $updateConfiguracion->activa        = $request->activa;
        $updateConfiguracion->estado_cierre = true;
        $updateConfiguracion->save();

        return response()->json(['msj'=>'Se han actualizado los datos de la caja.'],200);
    }

    public function cajasXsucursal($sucursal){
        return response()->json([
            'cajas' => Caja::with(['sucursal','user'])->where('sucursal_id', $sucursal)->get()
        ],200);
    }

    public function accederCaja(Request $request){
        $datosCaja = Caja::find($request->id);
        $cierreCaja = CierreCaja::where('caja_id',$request->id)->whereDate('fecha',date('Y-m-d'))->count();
        if (\Illuminate\Support\Facades\Hash::check($request->password, $datosCaja->password)
            && $request->user == $datosCaja->user_id && $datosCaja->activa == 1
            && $datosCaja->estado_cierre == 1 && $cierreCaja == 0){
            $datosCaja->estado_cierre = 1;
            $datosCaja->save();
            return response()->json(['msj'=>'Accediendo a la caja','caja_id'=>$datosCaja->id], 200);
        }else{
            return response()->json(['error'=>'La contraseña es incorrecta o esta caja no esta asignada a este usuario, o la caja está cerrada por hoy'], 422);
        }
    }

    public function historial_caja($caja, $fecha){
        $historial = HistorialCaja::with([
            'caja',
            'factura' => function($query){
                $query->with(['cliente']);
            },
            'recibo' => function($query){
                $query->with([
                    'factura' => function($query){
                        $query->with(['cliente']);
                    },
                ]);
            },
            'forma_pago',
            'retirada_efectivo' => function($query){
                $query->with([
                    'user',
                    'cuenta_banco' => function($query){
                        $query->with(['banco']);
                    }
                ]);
            }
        ])
            ->where('caja_id', $caja)
            ->whereDate('created_at',$fecha)
            ->orderBy('created_at','DESC')
            ->get();
        $caja = Caja::where('id',$caja)->first();


        return response()->json(['historial'=>$historial,'caja'=>$caja],200);
    }

    public function imprimirRetiradaEfectivo($user, $retirada, $token){
        $retiradaEfectivo = RetiradaEfectivo::with([
            'user' => function($query){
                $query->with(['colaborador']);
            },
            'cuenta_banco' => function($query){
                $query->with(['banco']);
            }
        ])->where('id', $retirada)->first();
        $formato = new NumeroALetras();
        $totalString = $formato->toMoney($retiradaEfectivo->total,2,'LEMPIRAS','CENTAVOS');
        $pdf = PDF::loadView('pdfs.retirada',[
            'empresa'     => DatosEmpresa::first(),
            'totalString' => $totalString,
            'retirada'    => $retiradaEfectivo,
            'usuario'     => $retiradaEfectivo->user
        ]);
        $pdf->setPaper(array(0,0,330,1500));
        return $pdf->stream('Retirada Efectivo - '.$retiradaEfectivo->comentario.'.pdf');
    }
}
