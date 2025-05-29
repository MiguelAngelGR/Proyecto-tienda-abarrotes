<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    public function run()
    {
        $proveedores = [
            [
                'nombre' => 'Distribuidora La Central',
                'contacto' => 'María González',
                'telefono' => '555-0123',
                'email' => 'ventas@lacentral.com',
                'direccion' => 'Av. Principal 123',
                'ciudad' => 'Ciudad de México',
                'estado' => 'CDMX',
                'codigo_postal' => '01234',
                'notas' => 'Proveedor principal de abarrotes'
            ],
            [
                'nombre' => 'Refrescos del Norte',
                'contacto' => 'Carlos Martínez',
                'telefono' => '555-0456',
                'email' => 'pedidos@refrescosnorte.com',
                'direccion' => 'Calle Industria 456',
                'ciudad' => 'Monterrey',
                'estado' => 'Nuevo León',
                'codigo_postal' => '64000',
                'notas' => 'Especialista en bebidas'
            ],
            [
                'nombre' => 'Dulces y Más',
                'contacto' => 'Ana López',
                'telefono' => '555-0789',
                'email' => 'info@dulcesymas.com',
                'direccion' => 'Boulevard Dulce 789',
                'ciudad' => 'Guadalajara',
                'estado' => 'Jalisco',
                'codigo_postal' => '44100',
                'notas' => 'Confitería y dulces'
            ]
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}