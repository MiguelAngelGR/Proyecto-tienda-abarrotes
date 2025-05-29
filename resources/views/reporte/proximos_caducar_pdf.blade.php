<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Productos Próximos a Caducar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #856404;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #fff3cd;
            font-weight: bold;
            color: #856404;
        }
        .proximo {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
        .alert {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Productos Próximos a Caducar</h1>
        <p>Sistema de Gestión de Tienda de Abarrotes</p>
        <p>Período: {{ $today->format('d/m/Y') }} - {{ $sevenDaysLater->format('d/m/Y') }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="alert">
        <strong>¡ATENCIÓN!</strong> Los siguientes productos caducarán en los próximos 7 días. Se recomienda priorizar su venta o aplicar descuentos.
    </div>

    @if($inventarioProximoCaducar->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Código de Barras</th>
                <th>Lote</th>
                <th>Cantidad</th>
                <th>Fecha Caducidad</th>
                <th>Días Restantes</th>
                <th>Precio Venta</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventarioProximoCaducar as $item)
            <tr class="proximo">
                <td>{{ $item->producto->nombre }}</td>
                <td>{{ $item->producto->codigo_barras }}</td>
                <td>{{ $item->lote }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                <td>{{ $today->diffInDays($item->fecha_caducidad) }} días</td>
                <td>${{ number_format($item->producto->precio_venta, 2) }}</td>
                <td>${{ number_format($item->cantidad * $item->producto->precio_venta, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
        <h3>Resumen de Productos por Caducar</h3>
        <p><strong>Total productos próximos a caducar:</strong> {{ $inventarioProximoCaducar->count() }} registros</p>
        <p><strong>Total unidades:</strong> {{ $inventarioProximoCaducar->sum('cantidad') }}</p>
        <p><strong>Valor potencial de venta:</strong> ${{ number_format($inventarioProximoCaducar->sum(function($item) { return $item->cantidad * $item->producto->precio_venta; }), 2) }}</p>
        <p><strong>Recomendación:</strong> Aplicar descuentos del 20-30% para acelerar la venta.</p>
    </div>
    @else
    <div style="text-align: center; padding: 50px; color: #28a745;">
        <h3>¡Perfecto!</h3>
        <p>No hay productos próximos a caducar en los próximos 7 días.</p>
    </div>
    @endif

    <div class="footer">
        Página 1 de 1 - Generado automáticamente
    </div>
</body>
</html>