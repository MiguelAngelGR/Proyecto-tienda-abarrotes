// Archivo: resources/views/reporte/productos_pdf.blade.php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catálogo de Productos</title>
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
        .precio {
            text-align: right;
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
        <h1>Catálogo de Productos</h1>
        <p>Sistema de Gestión de Tienda de Abarrotes</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>Resumen del Catálogo</h3>
        <p><strong>Total de productos:</strong> {{ $productos->count() }}</p>
        <p><strong>Valor total inventario (compra):</strong> ${{ number_format($productos->sum(function($p) { return $p->precio_compra * $p->stock; }), 2) }}</p>
        <p><strong>Valor total inventario (venta):</strong> ${{ number_format($productos->sum(function($p) { return $p->precio_venta * $p->stock; }), 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->codigo_barras }}</td>
                <td>{{ $producto->categoria }}</td>
                <td>{{ $producto->marca }}</td>
                <td class="precio">${{ number_format($producto->precio_compra, 2) }}</td>
                <td class="precio">${{ number_format($producto->precio_venta, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td>{{ $producto->proveedor->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página 1 de 1 - Generado automáticamente
    </div>
</body>
</html>