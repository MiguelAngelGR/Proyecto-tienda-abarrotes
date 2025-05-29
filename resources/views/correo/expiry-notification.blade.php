<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alerta de Productos Próximos a Caducar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .warning {
            background-color: #fff3cd;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Alerta de Productos Próximos a Caducar</h1>
    </div>
    
    <div class="content">
        <p>Estimado/a Administrador/a,</p>
        
        <p>Le informamos que los siguientes productos están próximos a caducar en los próximos {{ $daysThreshold }} días:</p>
        
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha de Caducidad</th>
                    <th>Días Restantes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventoryItems as $item)
                <tr class="warning">
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->expiry_date->format('d/m/Y') }}</td>
                    <td>{{ $item->expiry_date->diffInDays(now()) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <p>Por favor, revise estos productos y tome las acciones necesarias.</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no responda a este mensaje.</p>
        <p>&copy; {{ date('Y') }} Tienda de Abarrotes. Todos los derechos reservados.</p>
    </div>
</body>
</html>