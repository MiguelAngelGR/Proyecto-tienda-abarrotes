<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario</title>
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
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
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
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .caducado {
            background-color: #f8d7da;
            color: #721c24;
        }
        .proximo {
            background-color: #fff3cd;
            color: #856404;
        }
        .vigente {
            background-color: #d4edda;
            color: #155724;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Inventario General</h1>
        <p>Sistema de Gestión de Tienda de Abarrotes</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>Resumen del Inventario</h3>
        <p><strong>Total de registros:</strong> {{ $inventario->count() }}</p>
        <p><strong>Total de unidades:</strong> {{ $inventario->sum('cantidad') }}</p>
        <p><strong>Productos caducados:</strong> {{ $inventario->filter(function($item) { return $item->estaCaducado(); })->count() }}</p>
        <p><strong>Próximos a caducar:</strong> {{ $inventario->filter(function($item) { return $item->proximoACaducar(); })->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Código de Barras</th>
                <th>Lote</th>
                <th>Cantidad</th>
                <th>Fecha Caducidad</th>
                <th>Estado</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventario as $item)
            <tr class="{{ $item->getEstadoCaducidad() }}">
                <td>{{ $item->producto->nombre }}</td>
                <td>{{ $item->producto->codigo_barras }}</td>
                <td>{{ $item->lote }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                <td>
                    @if($item->estaCaducado())
                        Caducado
                    @elseif($item->proximoACaducar())
                        Próximo a caducar
                    @else
                        Vigente
                    @endif
                </td>
                <td>{{ $item->notas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página 1 de 1 - Generado automáticamente
    </div>
</body>
</html>