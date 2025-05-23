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
            font-weight: bold;
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
    <title>Comanda</title>
</head>
<body >
<div class="text-center arial" style="font-size: 9px;">
    <small style="font-size: 45px; font-weight: bold">{{$empresa->nombre_corto}}</small>
    <div>

        <small class="text-center" style="font-size: 40px; font-weight: bold">Comanda de Cocina</small>
        <div style="font-size: 35px">
            <small class="text-center" style="font-size: 15px">{{\Carbon\Carbon::parse($comanda->created_at)->format('Y/m/d h:i a')}}</small>
        </div>
        <div style="font-size: 35px">
            <small class="text-center" style="font-size: 15px">{{$usuario->colaborador->nombres}} {{$usuario->colaborador->apellidos}}</small>
        </div>
    </div>
</div>
<hr>
<table class="arial">
    <thead>
    <tr ><th colspan="2">* Cliente: {{$comanda->nombre}}</th></tr>
    </thead>
    <thead>
    <tr ><th colspan="2">* Mesa:{{$mesa->nombre}} @if($mesa->num > 0)<small>{{$mesa->num}}</small>@endif</th> </tr>
    </thead>
</table>
<br>
<br>
<table class="arial">
    <tbody>
    @foreach($comanda->cuerpo_comandas as $cuerpo)
        @if($cuerpo->cant > 0)
            @if($cuerpo->is_combo == 1)
                <tr style="border-top: solid 1px #1a202c"><td colspan="2">Plato {{($platos++) + 1}}</td></tr>
            @endif
            <tr >
                <td style="width: 10%; margin-left: 5px"> {{$cuerpo->cant}}</td>
                @if($cuerpo->articulo_id)
                    <td>{{$cuerpo->articulo->nombre}}</td>
                @endif
                @if($cuerpo->combo_id)
                    <td>{{$cuerpo->combo->nombre}}</td>
                @endif
            </tr>
        @else
            <tr >
                <td>***</td>
                @if($cuerpo->articulo_id)
                    <td>{{$cuerpo->articulo->nombre}}</td>
                @endif
            </tr>
        @endif
    @endforeach
    </tbody>
</table >
<br>
<br>
<br>
<table  class="arial">
    <thead>
    <tr ><th colspan="2">Comentario</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2">{{$comanda->comentario}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>
