<html lang="es">
<head>
    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #f0f0f0;
            padding: 6px 10px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-line {
            margin: 3px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 13px;
        }
    </style>
    <title>Orden de Compra</title>
</head>
<body >
    <h1>{{$empresa->nombre_fiscal}} - Purchase Order</h1>

    <div class="section-title">Company Information</div>
    <p class="info-line">Email: {{$empresa->email}}</p>
    <p class="info-line">Address: {{$empresa->direccion}}</p>
    <p class="info-line">Phone: {{$empresa->telefono}}</p>

    <div class="row">
        <div>* Order Date: {{\Carbon\Carbon::parse($orden->created_at)->format('m/d/y')}}</div>
        <div>* Estimated Delivery: {{\Carbon\Carbon::parse($orden->fecha_entrega)->format('m/d/y')}}</div>
    </div>

    <div class="section-title">Supplier Information</div>
    <p class="info-line">Company: {{$orden->proveedor->nombre}}</p>
    <p class="info-line">Address: {{$orden->proveedor->direccion}}</p>
    <p class="info-line">Phone: {{$orden->proveedor->telefono}}</p>
    <p class="info-line">Email: {{$orden->proveedor->email}}</p>

    <table>
        <thead>
        <tr>
            <th>Barcode</th>
            <th>Item</th>
            <th>Comment</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
            @foreach($orden->item_orden_compras as $item)
                <tr >
                    <td>@if($item->articulo_id){{$item->articulo->codigo_barras}}@else - @endif</td>
                    @if($item->articulo_id)
                        <td>{{$item->articulo->nombre}}</td>
                        <td>{{$item->comentario}}</td>
                    @else
                        <td colspan="2">{{$item->comentario}}</td>
                    @endif
                    <td>{{$item->cant}}</td>
                    <td>{{$empresa->moneda}} {{number_format($item->precio,2)}}</td>
                    <td>{{$empresa->moneda}} {{number_format($item->total,2)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Order Total: {{$empresa->moneda}} {{$orden->total}}</p>
</body>
</html>

