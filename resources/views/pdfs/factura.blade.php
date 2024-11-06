<html lang="es">
<head>
    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        table thead tr th{
            font-size: 20px;
            text-align: left;
        }
        table tbody tr td{
            font-size: 18px;
            padding: 4px;
        }
        table tbody tr th{
            font-size: 15px;
            text-align: end;
        }
        @page{margin: 0.1in 0.1in 0.1in 0.1in;}
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        .text-left{
            text-align: left;
        }
        .arial{
            font-family: Arial, Helvetica, sans-serif;
        }
        .hr{
            background-color: #252627;
            border: 0px;
            height: 1px;
            width: 100%;
        }
        .left{
            text-align: left;
        }
    </style>
    <title>Factura {{$factura->contador}}</title>
</head>
<body >
<div class="text-center arial" style="font-size: 9px;">
    <small style="font-size: 40px; font-weight: bold">{{$sucursal->nombre}}</small>
    <div><small style="font-size: 30px">{{$sucursal->direccion_completa}}</small></div>
    <div><small style="font-size: 25px">540 926 0578</small></div>
    <div style="margin-top: 45px"><small style="font-size: 35px; font-weight: bold">RECEIPT</small></div>
    <div class="text-center"><small style="font-size: 30px">{{$factura->contador}}</small></div>
</div>

<div class="arial">
    <div>
        <hr>
        <div><small style="font-size: 25px">{{\Carbon\Carbon::parse($factura->created_at)->monthName}} - {{\Carbon\Carbon::parse($factura->created_at)->isoFormat('dddd D, Y')}} </small></div>
        <div><small style="font-size: 25px">{{\Carbon\Carbon::parse($factura->created_at)->format('h:i:s a')}} </small></div>
        <hr>
        <div><small style="font-size: 22px">* {{$factura->forma_pago->nombre}}</small></div>
        <hr>
        <div><small style="font-size: 22px">Cashier: {{$user->usuario}}</small></div>
    </div>
    <div class="hr">
    <table class="arial">
        <thead>
        <tr >
            <th colspan="2">Item Name</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
            @foreach($cuerpo as $item)
               <tr>
                   @if($item->combo_id)
                       <td style="width: 50%">{{$item->combo->nombre}}</td>
                   @endif
                   @if($item->articulo_id)
                       @if($item->cantidad == 0)
                           <td style="width: 50%">***{{$item->articulo->nombre}} @if($item->precio_sides) {{$item->precio_sides}} @endif</td>
                       @else
                           <td style="width: 50%">{{$item->articulo->nombre}}</td>
                       @endif
                   @endif
                   <td style="width: 10%">{{$item->cantidad}} x</td>
                   <td style="width: 20%">{{$empresa->moneda}} {{number_format($item->precio - $item->sin_imp,2)}} </td>
                   <td style="width: 20%">{{$empresa->moneda}} {{number_format($item->total - $item->sin_imp_total,2)}} </td>
               </tr>
            @endforeach

            <tr>
                <td colspan="5"><div class="hr"></div></td>
            </tr>
            <tr>
                <th style="font-size: 20px" colspan="2" class="text-right">Custom Amount:</th>
                <th style="font-size: 20px" colspan="2" class="text-right">{{$empresa->moneda}} {{number_format($factura->total - $factura->impuesto_1,2)}}</th>
            </tr>
            <tr>
                <th style="font-size: 20px" colspan="2" class="text-right">Tax (5.3%):</th>
                <th style="font-size: 20px" colspan="2" class="text-right">{{$empresa->moneda}} {{number_format($factura->impuesto_1,2)}}</th>
            </tr>
            <tr>
                <th style="font-size: 20px" colspan="2" class="text-right">Total:</th>
                <th style="font-size: 20px" colspan="2" class="text-right">{{$empresa->moneda}} {{number_format($factura->total,2)}}</th>
            </tr>
            <tr>
                <td colspan="2" class="text-right" style="font-size: 20px">Cash Tendered:</td>
                <td style="font-size: 20px" colspan="2" class="text-right">{{$empresa->moneda}} {{number_format($factura->cobrado,2)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right" style="font-size: 20px">Change:</td>
                <td style="font-size: 20px" colspan="2" class="text-right">{{$empresa->moneda}} {{number_format($factura->cambio,2)}}</td>
            </tr>
        </tbody>
    </table>
        <br>
        <br>
        <div style="font-size: 20px" class="text-center">
            <small class="text-center" style="font-size: 20px; font-weight: bold">-----THANK YOU-----</small>
            <br>
            <small class="text-center" style="font-size: 20px; font-weight: bold">DIOS TE BENDIGA, GRACIAS POR TU COMPRA!!</small>
        </div>
    </div>
</div>
</body>
</html>
