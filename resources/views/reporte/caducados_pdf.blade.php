<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Productos Caducados</title>
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
            border-bottom: 2px solid #dc3545;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #dc3545;
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
            background-color: #f8d7da;
            font-weight: bold;
            color: #721c24;
        }
        .caducado {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Productos Caducados</h1>
        <p>Sistema de Gestión de Tienda de Abarrotes</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="alert">
        <strong>¡ATENCIÓN!</strong> Los siguientes productos han superado su fecha de caducidad y deben ser retirados del inventario inmediatamente.
    </div>

    @if($inventarioCaducado->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Código de Barras</th>
                <th>Lote</th>
                <th>Cantidad</th>
                <th>Fecha Caducidad</th>
                <th>Días Vencido</th>
                <th>Valor Pérdida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventarioCaducado as $item)
            <tr class="caducado">
                <td>{{ $item->producto->nombre }}</td>
                <td>{{ $item->producto->codigo_barras }}</td>
                <td>{{ $item->lote }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                <td>{{ $today->diffInDays($item->fecha_caducidad) }} días</td>
                <td>${{ number_format($item->cantidad * $item->producto->precio_compra, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
        <h3>Resumen de Pérdidas</h3>
        <p><strong>Total productos caducados:</strong> {{ $inventarioCaducado->count() }} registros</p>
        <p><strong>Total unidades:</strong> {{ $inventarioCaducado->sum('cantidad') }}</p>
        <p><strong>Valor total de pérdida:</strong> ${{ number_format($inventarioCaducado->sum(function($item) { return $item->cantidad * $item->producto->precio_compra; }), 2) }}</p>
    </div>
    @else
    <div style="text-align: center; padding: 50px; color: #28a745;">
        <h3>¡Excelente!</h3>
        <p>No hay productos caducados en el inventario.</p>
    </div>
    @endif

    <div class="footer">
        Página 1 de 1 - Generado automáticamente
    </div>
</body>
</html>