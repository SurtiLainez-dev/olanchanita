<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('registrar_empresa', [\App\Http\Controllers\UsuarioController::class, 'registroEmpresa']);
Route::post('login',             [\App\Http\Controllers\UsuarioController::class, 'login']);

//prueba

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group(['middleware' => 'checkclave'], function (){
    Route::prefix('print')->group(function (){
        Route::get( 'comanda_cocina/user={user}/comanda={id}/{clave}',      [\App\Http\Controllers\ComandasController::class,  'imprimirComanda']);
        Route::get('factura/user={user}/contador={contador}/{clave}',       [\App\Http\Controllers\FacturasController::class,  'print_factura']);
        Route::get('recibo/user={user}/contador={contador}/{clave}',        [\App\Http\Controllers\ReciboController::class,    'printRecibo']);
        Route::get('retirada_efectivo/user={user}/retirada={id}/{clave}',   [\App\Http\Controllers\CajaController::class,      'imprimirRetiradaEfectivo']);
        Route::get('cierre/user={user}/fecha={fecha}/{clave}/caja={caja}',  [\App\Http\Controllers\CierreCajaController::class,'imprimirCierre']);
        Route::get('reporte_cuentas/user={user}/cliente={id}/{clave}',[\App\Http\Controllers\FacturasController::class,'reporteCuentas']);
        Route::get('cuenta/user={user}/cuenta={contador}/{clave}',[\App\Http\Controllers\CuentaController::class,'imprimirCuenta']);
    });

    Route::prefix('reportes')->group(function (){
        Route::get('inventario_x_sucursal/usuario={user}/sucursal={id}/{clave}',[\App\Http\Controllers\ArticuloController::class,'excel']);
    });
});
Route::group(['middleware' => 'auth:api'], function (){
    Route::post('logout',                      [\App\Http\Controllers\UsuarioController::class,'logout']);
    Route::get( 'formas_pagos',                  [\App\Http\Controllers\ImpuestoController::class,      'indexFormasPagos']);
    Route::get( 'impuestos',                     [\App\Http\Controllers\ImpuestoController::class,      'index']);
    Route::post('solicitar_clave_doucmento',    [\App\Http\Controllers\AuthDocumentosController::class, 'solicitud']);
    Route::get( 'tipos_usuarios',               [\App\Http\Controllers\UsuarioController::class,        'tiposUsuarios']);
    Route::post('usuarios',                     [\App\Http\Controllers\UsuarioController::class,        'store']);
    Route::get( 'puestos_colaborador',          [\App\Http\Controllers\ColaboradoresController::class,  'puestos']);
    Route::post('puesto_colaborador',           [\App\Http\Controllers\ColaboradoresController::class,  'storePuestoColaborador']);
    Route::get( 'colaboradores',                [\App\Http\Controllers\ColaboradoresController::class, 'index']);
    Route::post('colaboradores',                [\App\Http\Controllers\ColaboradoresController::class, 'store']);
    Route::get( 'colaboradores/{col}',          [\App\Http\Controllers\ColaboradoresController::class, 'show']);
    Route::get( 'colaboradores_suc/{sucursal}', [\App\Http\Controllers\ColaboradoresController::class,  'colaboradoresXsucursal']);
    Route::get( 'sucursales',                   [\App\Http\Controllers\SucursalController::class,       'index']);
    Route::get( 'estados_articulos',            [\App\Http\Controllers\ArticuloController::class,       'estados_articulos']);
    Route::post('mesas',                        [\App\Http\Controllers\MesasController::class,          'store']);
    Route::get( 'mesas',                        [\App\Http\Controllers\MesasController::class,          'index']);
    Route::get( 'proveedores',                  [\App\Http\Controllers\ProveedorController::class,      'index']);
    Route::post('proveedores',                  [\App\Http\Controllers\ProveedorController::class,      'store']);
    Route::get( 'proveedores/{id}',             [\App\Http\Controllers\ProveedorController::class,      'show']);
    Route::get( 'proveedores_x_marca/{marca}',  [\App\Http\Controllers\ProveedorController::class,      'proveedorXmarca']);
    Route::get( 'bancos',                       [\App\Http\Controllers\BancoController::class,          'index']);
    Route::post('bancos',                       [\App\Http\Controllers\BancoController::class,          'store']);
    Route::get( 'cuentas',                      [\App\Http\Controllers\BancoController::class,          'indexCuentas']);
    Route::post('cuentas',                      [\App\Http\Controllers\BancoController::class,          'storeCuentaBanco']);
    Route::get( 'tipo_cuenta',                  [\App\Http\Controllers\BancoController::class,          'indexTipo']);

    Route::get( 'marcas_proveedor',              [\App\Http\Controllers\MarcaController::class,'index']);
    Route::post('marcas_proveedor',              [\App\Http\Controllers\MarcaController::class,'store']);
    Route::post('nueva_marca_proveedor',         [\App\Http\Controllers\MarcaController::class,'storeNuevoProveedor']);
    Route::put( 'marcas_proveedor/{id}',         [\App\Http\Controllers\MarcaController::class,'update']);

    Route::post('familias',                      [\App\Http\Controllers\FamiliaArticuloController::class,'store']);
    Route::get( 'familias',                      [\App\Http\Controllers\FamiliaArticuloController::class,'index']);
    Route::post('familia/edit',                  [\App\Http\Controllers\FamiliaArticuloController::class,'update']);
    Route::post('sub_familias',                  [\App\Http\Controllers\FamiliaArticuloController::class,'storeS']);
    Route::get( 'sub_familias',                  [\App\Http\Controllers\FamiliaArticuloController::class,'indexS']);
    Route::post('sub_familias/edit',             [\App\Http\Controllers\FamiliaArticuloController::class,'updateS']);

    Route::post('articulos',                      [\App\Http\Controllers\ArticuloController::class,'store']);
    Route::put( 'articulo/{id}',                      [\App\Http\Controllers\ArticuloController::class,'update']);
    Route::get( 'articulos',                      [\App\Http\Controllers\ArticuloController::class,'articulos']);
    Route::get( 'consultar_articulo/{tipo}/{id}', [\App\Http\Controllers\ArticuloController::class,'index']);
    Route::get( 'articulo/{id}',                  [\App\Http\Controllers\ArticuloController::class,'show']);
    Route::post('articulo/cambiar_visibilidad',   [\App\Http\Controllers\ArticuloController::class,'cambiarVisibilidad']);
    Route::post('articulo/stock_padre',           [\App\Http\Controllers\StockPadreController::class,'store']);
    Route::get( 'articulo/stock_padre/{art}',     [\App\Http\Controllers\StockPadreController::class,'show']);
    Route::put( 'articulo/stock_padre/{art}',    [\App\Http\Controllers\StockPadreController::class,'update']);
    Route::get( 'inventario_x_proveedor/{id}',   [\App\Http\Controllers\ArticuloController::class,'inventarioProveedor']);
    Route::get( 'inventario_x_sucursal/{id}',    [\App\Http\Controllers\ArticuloController::class,'inventarioSucursal']);
    Route::post('ordenes_entrada',               [\App\Http\Controllers\OrdenEntradasController::class,'store']);
    Route::get( 'ordenes_entrada',               [\App\Http\Controllers\OrdenEntradasController::class,'index']);
    Route::get( 'ordenes_entrada/{id}',          [\App\Http\Controllers\OrdenEntradasController::class,'show']);
    Route::get( 'ordenes_entradas/pendientes',   [\App\Http\Controllers\OrdenEntradasController::class,'pendientesFactura']);
    Route::post('orden_salida',                  [\App\Http\Controllers\OrdenSalidaController::class,'store']);
    Route::get( 'orden_salida',                  [\App\Http\Controllers\OrdenSalidaController::class,'index']);
    Route::get( 'orden_salida/{id}',             [\App\Http\Controllers\OrdenSalidaController::class,'show']);
    Route::post('precio_articulo',               [\App\Http\Controllers\ArticuloController::class,'storePrecio']);
    Route::get( 'tipos_entrada_articulos',       [\App\Http\Controllers\ArticuloController::class,'tipoEntradas']);
    Route::get( 'cajas/configuracion/impresion', [\App\Http\Controllers\DirectricezImpresionController::class,'index']);
    Route::post( 'cajas/configuracion/impresion',[\App\Http\Controllers\DirectricezImpresionController::class,'store_directriz']);
    Route::get( 'cajas',                         [\App\Http\Controllers\CajaController::class,'index']);
    Route::post('cajas',                         [\App\Http\Controllers\CajaController::class,'store']);
    Route::get( 'caja/{id}',                     [\App\Http\Controllers\CajaController::class,'show']);
    Route::post('cajas/configuracion',           [\App\Http\Controllers\CajaController::class,'storeConfiguracion']);
    Route::get( 'cajas/cajas',                   [\App\Http\Controllers\CajaController::class,'cajas']);
    Route::get( 'cajas/caja/{caja}',             [\App\Http\Controllers\CajaSucursalController::class,'show']);
    Route::get( 'cajas/cajas_x_sucursal/{sucursal}', [\App\Http\Controllers\CajaController::class,'cajasXsucursal']);
    Route::post('cajas/acceder_caja',                 [\App\Http\Controllers\CajaController::class,'accederCaja']);
    Route::get( 'buscar_user/{sucursal}',             [\App\Http\Controllers\SucursalController::class,'usuarios']);
    Route::post('cajas/registrar_egreso',             [\App\Http\Controllers\CajaController::class,'registrarEgreso']);

    Route::post('combos',                             [\App\Http\Controllers\CombosController::class,'store']);
    Route::post('combos_sides',                       [\App\Http\Controllers\CombosController::class,'storeSide']);
    Route::get( 'combos',                             [\App\Http\Controllers\CombosController::class,'index']);
    Route::put( 'combo/{id}',                         [\App\Http\Controllers\CombosController::class,'update']);
    Route::post('clientes',                           [\App\Http\Controllers\ClienteController::class,'store']);
    Route::get( 'clientes',                           [\App\Http\Controllers\ClienteController::class,'index']);
    Route::post('comandas',                           [\App\Http\Controllers\ComandasController::class,'store']);
    Route::get( 'comandas/fecha/{fecha}/all/{all}',   [\App\Http\Controllers\ComandasController::class,'index']);
    Route::get( 'comandas/{id}',                      [\App\Http\Controllers\ComandasController::class,'show']);
    Route::put( 'comandas/{id}',                      [\App\Http\Controllers\ComandasController::class,'update']);
    Route::post('factura',                            [\App\Http\Controllers\FacturasController::class,'store']);
    Route::get( 'factura/{id}',                       [\App\Http\Controllers\FacturasController::class,'show']);
    Route::post('factura_proveedor',                  [\App\Http\Controllers\FacturaProveedorController::class,'store']);
    Route::get( 'facturas_proveedores',               [\App\Http\Controllers\FacturaProveedorController::class,'index']);
    Route::get( 'factura_proveedor/{id}',                  [\App\Http\Controllers\FacturaProveedorController::class,'show']);
//    Route::get( 'facturas/fecha/{fecha}',                    [\App\Http\Controllers\FacturasController::class,'index']);
    Route::get('historial_caja/caja/{caja}/fecha/{fecha}', [\App\Http\Controllers\CajaController::class,'historial_caja']);
    Route::post('cierre_caja',                             [\App\Http\Controllers\CierreCajaController::class,'store']);
    Route::get( 'consulta_cierres/fechas',                 [\App\Http\Controllers\CierreCajaController::class,'consultaFecha']);
    Route::get( 'cierre_caja/caja/{caja}/fecha/{fecha}',   [\App\Http\Controllers\CierreCajaController::class,'index']);
    Route::post('recibo',                                  [\App\Http\Controllers\ReciboController::class,'store']);
    Route::post('cuenta_cliente',                          [\App\Http\Controllers\CuentaController::class,'store']);
    Route::get('cuentas_pendientes',                       [\App\Http\Controllers\CuentaController::class,'cuentasPendientes']);

    Route::post('impresora',     [\App\Http\Controllers\ConfiguracionImpresoras::class,'store']);
    Route::get( 'impresora',     [\App\Http\Controllers\ConfiguracionImpresoras::class,'index']);
    Route::put( 'impresora/{id}',[\App\Http\Controllers\ConfiguracionImpresoras::class,'update']);

    Route::post('orden_porcion', [\App\Http\Controllers\OrdenPorcionController::class,'store']);
    Route::get( 'orden_procion', [\App\Http\Controllers\OrdenPorcionController::class,'index']);

    Route::post('tipo_gasto', [\App\Http\Controllers\GastoController::class,'storeTipoGasto']);
    Route::get( 'tipo_gasto', [\App\Http\Controllers\GastoController::class,'indexTipoGasto']);
//    Route::post('gastos',     [\App\Http\Controllers\GastoController::class,'store']);
});
