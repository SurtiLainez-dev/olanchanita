<html lang="es">
<head>
    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        table thead tr th{
            font-size: 14px;
            text-align: left;
        }
        table tbody tr td{
            font-size: 15px;
        }
        table tbody tr th{
            font-size: 18px;
            text-align: end;
        }
        @page{margin: 0.1in 0.1in 0.1in 0.1in;}
        .text-center{
            text-align: center;
        }
        .arial{
            font: 150% sans-serif ;
        }
        hr{
            background-color: #808B96;
            border: 0px;
        }
        .left{
            text-align: left;
        }
    </style>
    <title>RECIBO # {{$recibo->contador}}</title>
</head>
<body >
<div class="text-center arial" style="font-size: 9px;">
    <small style="font-size: 40px; font-weight: bold">{{$empresa->nombre_corto}}</small>
    <div><small style="font-size: 20px">{{$empresa->direccion}}</small></div>
</div>
<hr>
<div class="text-center arial" style="font-size: 9px;">
    <div style="margin-top: 10px"><small style="font-size: 30px; font-weight: bold">Receipt</small></div>
    <div><small style="font-size: 25px; font-weight: bold; margin-top: 20px">{{$recibo->contador}}</small></div>
</div>
<hr>
<div class="arial">
    <div>
        <div><small style="font-size: 20px">Pay: {{$forma_pago->nombre}}</small></div>
        <div><small style="font-size: 20px">Date&Time: {{\Carbon\Carbon::parse($recibo->created_at)->format('m/d/Y H:i:s')}}</small></div>
        <div><small style="font-size: 20px">Cashier Cod.: {{$caja->codigo}}</small></div>
        <div><small style="font-size: 20px">User: {{$colaborador->nombres}} {{$colaborador->apellidos}}</small></div>
    </div>
    <hr>
    @if($recibo->factura)
        <div>
            <div><small style="font-size: 20px">CLIENTE: {{$recibo->factura->cliente->nombre}}</small></div>
            <div><small style="font-size: 20px">RTN: {{$recibo->factura->cliente->rtn}}</small></div>
        </div>
    @endif
    <div>
        <div><small style="font-size: 20px">About: {{$recibo->comentario}}</small></div>
    </div>
    <div>
        <div><small style="font-size: 20px">Total: <strong>{{$empresa->moneda}} {{number_format($recibo->total,2)}}</strong></small></div>
    </div>

    @if($recibo->factura)
        <hr>
        <table>
            <thead>
            <tr>
                <th># Factura</th>
                <th>Total</th>
                <th>Abonado</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$recibo->factura->contador}}</td>
                <td>L. {{number_format($recibo->factura->total, 2)}}</td>
                <td>L. {{number_format($recibo->factura->cobrado, 2)}}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">----- End -----</td>
            </tr>
            </tbody>
        </table>
    @endif

    @if($recibo->cuenta)
        <hr>
        <table>
            <thead>
            <tr>
                <th colspan="2"># Cuenta</th>
                <th style="font-size: 12px">S. Inicial</th>
                <th style="font-size: 12px">S. Actual</th>
            </tr>
            </thead>
            <tbody>
            <tr style="border-bottom: solid 1px #808B96">
                <td colspan="2" style="font-size: 12px">{{$recibo->cuenta->contador}} - {{$recibo->cuenta->nombre}}</td>
                <td style="font-size: 12px">L. {{number_format($recibo->cuenta->saldo_inicial, 2)}}</td>
                <td style="font-size: 12px">L. {{number_format($recibo->cuenta->saldo_actual, 2)}}</td>
            </tr>
            </tbody>
            <thead>
            <tr>
                <th>Cliente</th>
                <th>Factura</th>
                <th>Total</th>
                <th>Cobrado</th>
            </tr>
            </thead>
            <tbody>
            @foreach($recibo->cuenta->cuerpo_cuentas as $item)
                <tr>
                    <td style="font-size: 12px !important;">@if($item->factura->cancelado == 0)*@endif{{$item->factura->cliente->nombre}}</td>
                    <td style="font-size: 12px">{{$item->factura->contador}}</td>
                    <td style="font-size: 10px">L.{{number_format($item->factura->total,2)}}</td>
                    <td style="font-size: 12px">L.{{number_format($item->factura->cobrado,2)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="font-size: 16px" class="text-center">----- Fín -----</td>
            </tr>
            <tr>
                <td colspan="4" style="font-size: 16px">Las cuentas que tienen un * al inicio, estan pendientes de cancelar</td>
            </tr>
            </tbody>
        </table>
    @endif


    <div style="margin-top: 100px">
        <div style="font-size: 16px;" class="text-center">_______________________________________</div>
        <div style="font-size: 15px;" class="text-center">SIGNATURE</div>
    </div>
</div>
</body>
</html>
