<html lang="es">
<head>
    {{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        table tr th{
            font-size: 22px;
            text-align: left;
        }
        table tr td{
            font-size: 18px;
        }
        table thead tr th{
            font-size: 20px;
            text-align: left;
        }
        table tbody tr td{
            font-size: 16px;
        }
        table tbody tr th{
            font-size: 20px;
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
    <title>CIERRE DE CAJA {{$fecha}}</title>
</head>
<body >
    <div class="arial">
        <div class="text-center arial" style="font-size: 9px;">
            <small style="font-size: 40px; font-weight: bold">{{$empresa->nombre_corto}}</small>
            <div>

                <small class="text-center" style="font-size: 40px; font-weight: bold">CIERRE DE CAJA</small>
                <div style="font-size: 10px">
                    <small class="text-center" style="font-size: 22px">{{\Carbon\Carbon::parse($cierre->created_at)->format('Y/m/d h:i a')}}</small>
                </div>
                <div style="font-size: 10px">
                    <small class="text-center" style="font-size: 22px">{{$usuario}} - {{$colaborador->nombres}} {{$colaborador->apellidos}}</small>
                </div>
            </div>
        </div>
        <hr>
        <table>
            <tr>
                <th>*Resumen de saldos</th>
            </tr>
            <tr>
                <th>Fecha</th>
                <td>{{\Carbon\Carbon::parse($fecha)->format('d/m/Y')}}</td>
            </tr>
            <tr>
                <th>Total de ingresos </th>
                <td>L. {{number_format($cierre->total,2)}}</td>
            </tr>
            <tr>
                <th>Total de efectivo ingresado</th>
                <td>L. {{number_format($cierre->total_efectivo,2)}}</td>
            </tr>
            <tr>
                <th>Total de tarjeta cobrado</th>
                <td>L. {{number_format($cierre->total_tarjeta,2)}}</td>
            </tr>
            <tr>
                <th>Total de egresos</th>
                <td>L. {{number_format($cierre->egresos,2)}}</td>
            </tr>
            <tr>
                <th>Efectivo al final de cierre</th>
                <td>L. {{number_format($cierre->efectivo_final_dia,2)}}</td>
            </tr>
            <tr>
                <th>Efectivo declarado</th>
                <td>L. {{number_format($cierre->efectivo_declarado,2)}}</td>
            </tr>
            <tr>
                <th>Descuadre</th>
                <td>L. {{number_format($cierre->descuadre,2)}}</td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <th>*Formas de pagos antes del cierre</th>
            </tr>
            <thead>
            <tr>
                <th>Forma de Pago</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($forma_pagos as $forma)
                <tr>
                    <td>{{$forma->nombre}}</td>
                    <td>L. {{number_format($forma->total,2)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-center">--- Última Línea ---</td>
            </tr>
            </tbody>
        </table>
{{--        <hr>--}}
{{--        <table>--}}
{{--            <tr>--}}
{{--                <th>Resumen de ingresos</th>--}}
{{--            </tr>--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th>Detalle</th>--}}
{{--                <th>Total</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($historial as $ingreso)--}}
{{--                @if($ingreso->tipo_documento == 2 || $ingreso->tipo_documento == 3)--}}
{{--                    <tr>--}}
{{--                        <td>{{$ingreso->referencia}}</td>--}}
{{--                        <td>L. {{number_format($ingreso->recibo->total, 2)}}</td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--            <tr>--}}
{{--                <td colspan="2" class="text-center">--- Última Línea ---</td>--}}
{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--        <hr>--}}
{{--        <table>--}}
{{--            <tr>--}}
{{--                <th>Resumen de egresos</th>--}}
{{--            </tr>--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th>Detalle</th>--}}
{{--                <th>Total</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--                @foreach($historial as $egreso)--}}
{{--                    @if($egreso->tipo_documento == 4)--}}
{{--                        <tr>--}}
{{--                            <td>{{$egreso->referencia}}</td>--}}
{{--                            <td>L. {{number_format($egreso->total, 2)}}</td>--}}
{{--                        </tr>--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--                <tr>--}}
{{--                    <td colspan="2" class="text-center">--- Última Línea ---</td>--}}
{{--                </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
        <hr>
{{--        <table>--}}
{{--            <tr>--}}
{{--                <th colspan="2">Ventas por familia</th>--}}
{{--            </tr>--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th>Cant./Nombre</th>--}}
{{--                <th>Total</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($resumen1 as $item)--}}
{{--                <tr>--}}
{{--                    <td>{{$item->cant}} - {{$item->nombre}}</td>--}}
{{--                    <td>{{$empresa->moneda}} {{number_format($item->total,2)}}</td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            <tr>--}}
{{--                <td colspan="2" class="text-center">--- Última Línea ---</td>--}}
{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--        <hr>--}}
{{--        <table>--}}
{{--            <tr>--}}
{{--                <th colspan="2">Ventas por combos</th>--}}
{{--            </tr>--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th>Cant./Nombre</th>--}}
{{--                <th>Total</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($resumen2 as $item)--}}
{{--                <tr>--}}
{{--                    <td>{{$item->cant}} - {{$item->nombre}}</td>--}}
{{--                    <td>L. {{number_format($item->total,2)}}</td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            <tr>--}}
{{--                <td colspan="2" class="text-center">--- Última Línea ---</td>--}}
{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--        <hr>--}}
{{--        <table>--}}
{{--            <tr>--}}
{{--                <th>Cantidad de items vendidos</th>--}}
{{--            </tr>--}}
{{--            <tbody>--}}
{{--            @foreach($resumen3 as $item)--}}
{{--                <tr>--}}
{{--                    <td>{{$item['cant']}} - {{$item['nombre']}}</td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            <tr>--}}
{{--                <td colspan="2" class="text-center">--- Última Línea ---</td>--}}
{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
        <hr>
        <table>
            <tr>
                <th>Cantidad de operaciones</th>
                <td>{{$historial->count()}}</td>
            </tr>
        </table>
{{--        <hr>--}}
{{--        <table>--}}
{{--            <tr>--}}
{{--                <td colspan="2">Otras operaciones</td>--}}
{{--            </tr>--}}
{{--            <thead>--}}
{{--                <tr>--}}
{{--                    <th>Operación</th>--}}
{{--                    <th style="text-align: right">Cant.</th>--}}
{{--                </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            <tr>--}}
{{--                <th>Cantidad de ventas al contado</th>--}}
{{--                <td style="text-align: right"> -------------------- {{$historial->where('tipo_documento',1)->count()}}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Cantidad de ventas al créditos</th>--}}
{{--                <td style="text-align: right"> -------------------- {{$historial->where('tipo_documento',5)->count()}}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Cantidad de recibos</th>--}}
{{--                <td style="text-align: right"> -------------------- {{$historial->where('tipo_documento',2)->count() + $historial->where('tipo_documento',3)->count()}}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Total de descuento aplicados</th>--}}
{{--                <td></td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Total de agresos para gastos</th>--}}
{{--                <td></td>--}}
{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
        <small style="font-size: 20px; margin-top: 0px !important; margin-left: 3px">Observaciones</small>
        <div style="height: 130px; width: 100%; border: solid #1a202c 1px">
        </div>

        <small style="font-size: 20px; margin-top: 0px !important; margin-left: 3px">Firma del cajero</small>
        <div style="height: 90px; width: 100%; border: solid #1a202c 1px">
        </div>

        <div class="text-center">
            <small style="font-size: 22px;">----- Fin del Cierre -----</small>
        </div>
        <div class="text-center">
            <small style="font-size: 30px;">----- TILK -----</small>
        </div>
        <hr>
    </div>
</body>
</html>
