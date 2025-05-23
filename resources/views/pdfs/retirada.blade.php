<html lang="es">
<head>
    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        table thead tr th{
            font-size: 22px;
            text-align: left;
        }
        table tbody tr td{
            font-size: 21px;
        }
        table tbody tr th{
            font-size: 11px;
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
    <title>RETIRADA DE EFECTIVO # {{$retirada->id}}</title>
</head>
<body >
<div class="text-center arial" style="font-size: 9px;">
    <small style="font-size: 45px; font-weight: bold">{{$empresa->nombre_corto}}</small>
    <div>

        <small class="text-center" style="font-size: 33px; font-weight: bold">RETIRADA DE EFECTIVO</small>
        <div style="font-size: 10px">
            <small class="text-center" style="font-size: 15px">{{\Carbon\Carbon::parse($retirada->created_at)->format('Y/m/d h:i a')}}</small>
        </div>
        <div style="font-size: 10px">
            <small class="text-center" style="font-size: 15px">{{$usuario->colaborador->nombres}} {{$usuario->colaborador->apellidos}}</small>
        </div>
    </div>
</div>
<hr>
<table class="arial">
    <thead>
    <tr ><th># {{$retirada->id}}</th></tr>
    </thead>
    <thead>
    <tr ><th>Tipo de Salida</th></tr>
    </thead>
    <tbody>
        <tr><td> --> {{$retirada->tipo_salida}}</td></tr>
    </tbody>
    <thead>
    <tr ><th>Comentario</th></tr>
    </thead>
    <tbody>
    <tr>
        <td > --> {{$retirada->comentario}}</td>
    </tr>
    </tbody>
    <thead>
    <tr ><th>Tipo de Salida</th></tr>
    </thead>
    <tbody>
    <tr>
        <td > --> {{$retirada->tipo_salida}}</td>
    </tr>
    </tbody>
    @if($retirada->tipo_salida == 'POR DEPOSITO')
        <thead>
        <tr ><th>*Cuenta de Banco</th></tr>
        </thead>
        <tbody>
        <tr>
            <td > --> {{$retirada->cuenta_banco->num}} - {{$retirada->cuenta_banco->banco->nombre}}</td>
        </tr>
        </tbody>
    @endif
    <thead>
    <tr ><th>Total</th></tr>
    </thead>
    <tbody>
    <tr>
        <td > --> {{number_format($retirada->total, 2)}}</td>
    </tr>
    <tr>
        <td > --> {{$totalString}}</td>
    </tr>
    </tbody>
</table>

<div style="margin-top: 90px">
    <div style="font-size: 10px;" class="text-center arial">________________________________________________________</div>
    <div style="font-size: 15px;" class="text-center arial">Firma</div>
</div>
</body>
</html>
