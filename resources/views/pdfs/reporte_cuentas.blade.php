<html lang="es">
<head>
    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        table thead tr th{
            font-size: 18px;
            text-align: left;
        }
        table tbody tr td{
            font-size: 18px;
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
    <title>Reporte de Cuentas</title>
</head>
<body >
<div class="text-center arial" style="font-size: 9px;">
    <small style="font-size: 45px; font-weight: bold">{{$empresa->nombre_corto}}</small>
    <div>

        <small class="text-center" style="font-size: 40px; font-weight: bold">Reporte de Cuentas</small>
        <div style="font-size: 35px">
            <small class="text-center" style="font-size: 15px">{{$user}}</small>
        </div>
    </div>
</div>
<hr>
<table class="arial">
    <thead>
    @if($cliente == null)
        <tr ><th colspan="2">* REPORTE GENERAL</th></tr>
    @else
        <tr ><th colspan="2">* Cliente: {{$cliente->nombre}}</th></tr>
    @endif
    </thead>
    <thead>
    <tr ><th colspan="2">* Total: L. {{number_format($total, 2)}}</tr>
    </thead>
    <thead>
    <tr ><th colspan="2">* Total Cobrado: L. {{number_format($cobrado, 2)}}</th> </tr>
    </thead>
    <thead>
    <tr ><th colspan="2">* Saldo Pendiente: L. {{number_format($total - $cobrado, 2)}}</th> </tr>
    </thead>
</table>
<br>
<br>
<table class="arial">
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Contador</th>
        <th>Pendiente</th>
    </tr>
    </thead>
    <tbody>
    @if($cliente == null)
        <tr>
            <td colspan="3">--------------------------------------------------------------------</td>
        </tr>
    @else
        @foreach($facturas as $factura)
            @if($cliente == null)
                <tr>
                    <td colspan="3" ><small style="font-size: 12px !important;">* {{$factura->cliente->nombre}}</small></td>
                </tr>
            @endif
            <tr>
                <td>{{\Carbon\Carbon::parse($factura->created_at)->format('d/m/Y')}}</td>
                <td>{{$factura->contador}}</td>
                <td>L. {{number_format($factura->total - $factura->cobrado)}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table >
</body>
</html>
