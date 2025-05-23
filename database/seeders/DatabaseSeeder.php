<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\EstadoArticulo;
use App\Models\FormaPago;
use App\Models\Impuesto;
use App\Models\TipoCuentaBanco;
use App\Models\TipoEntradaArticulo;
use App\Models\TipoUsuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $nuevoTipoUsuario = new TipoUsuario();
        $nuevoTipoUsuario->nombre = 'Colaborador';
        $nuevoTipoUsuario->save();

        $nuevoImpuesto = new Impuesto();
        $nuevoImpuesto->nombre = '5.3%';
        $nuevoImpuesto->porcentaje = 0.053;
        $nuevoImpuesto->is_activo  = true;
        $nuevoImpuesto->id         = 1;
        $nuevoImpuesto->save();

        $nuevoImpuesto = new Impuesto();
        $nuevoImpuesto->nombre = 'EXCENTO';
        $nuevoImpuesto->porcentaje = 0;
        $nuevoImpuesto->is_activo  = false;
        $nuevoImpuesto->id         = 2;
        $nuevoImpuesto->save();

        $nuevoTipoCuentaBanco = new TipoCuentaBanco();
        $nuevoTipoCuentaBanco->nombre = 'Ahorro';
        $nuevoTipoCuentaBanco->id     = 1;
        $nuevoTipoCuentaBanco->save();
        $nuevoTipoCuentaBanco = new TipoCuentaBanco();
        $nuevoTipoCuentaBanco->nombre = 'Cheques';
        $nuevoTipoCuentaBanco->id     = 2;
        $nuevoTipoCuentaBanco->save();

        $TipoEntradasArticulos = ['ENTRADA POR FACTURA','-','ENTRADA MANUAL','SALIDA MANUAL'];
        for ($i=0; $i < count($TipoEntradasArticulos); $i++):
            $nuevoTipoEntradaArticulo = new TipoEntradaArticulo();
            $nuevoTipoEntradaArticulo->id = $i+1;
            $nuevoTipoEntradaArticulo->nombre = $TipoEntradasArticulos[$i];
            $nuevoTipoEntradaArticulo->save();
        endfor;

        $EstadosArticulos = ['NUEVO','CONSIGNADO','REINGRESO','GARANTIA','VENDIDO'];
        for ($i=0; $i < count($EstadosArticulos); $i++):
            $nuevoEstadoArticulo = new EstadoArticulo();
            $nuevoEstadoArticulo->id = $i+1;
            $nuevoEstadoArticulo->nombre = $EstadosArticulos[$i];
            $nuevoEstadoArticulo->save();
        endfor;

//        $nuevoCliente = new Cliente();
//        $nuevoCliente->nombre = 'CONSUMIDOR FINAL';
//        $nuevoCliente->rtn    = '0000000000000';
//        $nuevoCliente->id     = 1;
//        $nuevoCliente->telefono = '00000000';
//        $nuevoCliente->save();

        $formasPagos = ['CHEQUES','TRANSFERENCIA','EFECTIVO','DEPÓSITO','TARJETA','EGRESOS DE CAJA','CREDITO'];
        for ($i=0; $i < count($formasPagos); $i++):
            $nuevaFormaPago = new FormaPago();
            $nuevaFormaPago->id = $i+1;
            $nuevaFormaPago->nombre = $formasPagos[$i];
            $nuevaFormaPago->save();
        endfor;
    }
}
