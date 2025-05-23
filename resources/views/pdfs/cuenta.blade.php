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
            font-size: 16px;
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
    <title>Cuenta #{{$cuenta->contador}}</title>
</head>
<body >
<div class="text-center arial" style="font-size: 9px;">
    <small style="font-size: 45px; font-weight: bold">{{$empresa->nombre_corto}}</small>
    <div>

        <small class="text-center" style="font-size: 40px; font-weight: bold">Cuenta #{{$cuenta->contador}}</small>
        <div style="font-size: 35px">
            <small class="text-center" style="font-size: 15px">{{$user}}</small>
        </div>
    </div>
</div>
<hr>
<table class="arial">
    <thead><tr ><th colspan="2">* Nombre: {{$cuenta->nombre}}</tr></thead>
    <thead><tr ><th colspan="2">* Contador: {{$cuenta->contador}}</tr></thead>
    <thead><tr ><th colspan="2">* Saldo Inicial: L. {{number_format($cuenta->saldo_inicial,2)}}</tr></thead>
    <thead><tr ><th colspan="2">* Saldo Actual: L. {{$cuenta->saldo_actual,2}}</tr></thead>
    <thead><tr ><th colspan="2">* Estado: @if($cuenta->estado == 0) Pendiente @else Cancelada @endif</tr></thead>
    <thead><tr ><th colspan="2">* Fecha de Creación: {{\Carbon\Carbon::parse($cuenta->created_at)->format('d/m/Y')}}</tr></thead>
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
        @foreach($facturas as $factura)
        <tr>
            <td colspan="3" ><small style="font-size: 12px !important;">* {{$factura->factura->cliente->nombre}}</small></td>
        </tr>
        <tr style="border-bottom: solid 1px #dde0e1">
            <td>{{\Carbon\Carbon::parse($factura->created_at)->format('d/m/Y')}}</td>
            <td>{{$factura->factura->contador}}</td>
            <td>L. {{number_format($factura->factura->total - $factura->factura->cobrado)}}</td>
        </tr>
        @endforeach
    </tbody>
</table >
</body>
</html>
