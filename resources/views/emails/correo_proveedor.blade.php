<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comunicación - Sistema de Tienda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .message {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema de Gestión de Tienda</h1>
        <p>Comunicación Comercial</p>
    </div>
    
    <div class="content">
        <p><strong>Estimado/a {{ $proveedor->contacto ?: $proveedor->nombre }},</strong></p>
        
        <div class="message">
            {!! nl2br(e($contenido)) !!}
        </div>
        
        <p>Quedamos atentos a su respuesta.</p>
        
        <p><strong>Atentamente,</strong><br>
        {{ $remitente->name }}<br>
        Sistema de Gestión de Tienda</p>
    </div>
    
    <div class="footer">
        <p>Este correo fue enviado automáticamente desde nuestro sistema de gestión.</p>
        <p>Por favor, no responda directamente a este correo.</p>
    </div>
</body>
</html>
