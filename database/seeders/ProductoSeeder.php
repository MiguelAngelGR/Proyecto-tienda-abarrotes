<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Inventario;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $proveedores = Proveedor::all();
        
        $productos = [
            [
                'nombre' => 'Coca Cola 600ml',
                'codigo_barras' => '7501055319122',
                'descripcion' => 'Refresco de cola 600ml',
                'precio_compra' => 12.50,
                'precio_venta' => 18.00,
                'categoria' => 'Bebidas',
                'marca' => 'Coca Cola',
                'peso' => 0.6,
                'unidad_medida' => 'litros',
                'proveedor_id' => $proveedores->where('nombre', 'Refrescos del Norte')->first()->id ?? 1,
                'stock' => 0
            ],
            [
                'nombre' => 'Sabritas Naturales 45g',
                'codigo_barras' => '7500326100615',
                'descripcion' => 'Papas fritas naturales',
                'precio_compra' => 8.00,
                'precio_venta' => 12.00,
                'categoria' => 'Botanas',
                'marca' => 'Sabritas',
                'peso' => 0.045,
                'unidad_medida' => 'kg',
                'proveedor_id' => $proveedores->first()->id ?? 1,
                'stock' => 0
            ],
            [
                'nombre' => 'Galletas Marías Gamesa',
                'codigo_barras' => '7501043013455',
                'descripcion' => 'Galletas Marías 180g',
                'precio_compra' => 15.00,
                'precio_venta' => 22.00,
                'categoria' => 'Galletas',
                'marca' => 'Gamesa',
                'peso' => 0.18,
                'unidad_medida' => 'kg',
                'proveedor_id' => $proveedores->first()->id ?? 1,
                'stock' => 0
            ],
            [
                'nombre' => 'Leche Lala Entera 1L',
                'codigo_barras' => '7501199301567',
                'descripcion' => 'Leche entera pasteurizada',
                'precio_compra' => 20.00,
                'precio_venta' => 28.00,
                'categoria' => 'Lácteos',
                'marca' => 'Lala',
                'peso' => 1.0,
                'unidad_medida' => 'litros',
                'proveedor_id' => $proveedores->first()->id ?? 1,
                'stock' => 0
            ],
            [
                'nombre' => 'Chocolate Carlos V',
                'codigo_barras' => '7500478123456',
                'descripcion' => 'Chocolate con leche 25g',
                'precio_compra' => 6.00,
                'precio_venta' => 10.00,
                'categoria' => 'Dulces',
                'marca' => 'Carlos V',
                'peso' => 0.025,
                'unidad_medida' => 'kg',
                'proveedor_id' => $proveedores->where('nombre', 'Dulces y Más')->first()->id ?? 1,
                'stock' => 0
            ],
            [
                'nombre' => 'Yogurt Danone Fresa',
                'codigo_barras' => '7501234567890',
                'descripcion' => 'Yogurt de fresa 125g',
                'precio_compra' => 8.50,
                'precio_venta' => 13.00,
                'categoria' => 'Lácteos',
                'marca' => 'Danone',
                'peso' => 0.125,
                'unidad_medida' => 'kg',
                'proveedor_id' => $proveedores->first()->id ?? 1,
                'stock' => 0
            ],
            [
                'nombre' => 'Pan Bimbo Blanco',
                'codigo_barras' => '7501987654321',
                'descripcion' => 'Pan de caja blanco grande',
                'precio_compra' => 25.00,
                'precio_venta' => 35.00,
                'categoria' => 'Panadería',
                'marca' => 'Bimbo',
                'peso' => 0.68,
                'unidad_medida' => 'kg',
                'proveedor_id' => $proveedores->first()->id ?? 1,
                'stock' => 0
            ]
        ];

        foreach ($productos as $index => $productoData) {
            $producto = Producto::create($productoData);
            
            // Crear diferentes tipos de inventario según el producto
            $inventarios = [];
            
            if ($index < 3) {
                // Primeros 3 productos: inventario normal + próximos a caducar
                $inventarios = [
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(15, 35),
                        'fecha_caducidad' => Carbon::now()->addDays(rand(30, 90)),
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-1',
                        'notas' => 'Inventario inicial - stock bueno'
                    ],
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(5, 12),
                        'fecha_caducidad' => Carbon::now()->addDays(rand(3, 6)),
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-2',
                        'notas' => 'Lote próximo a caducar'
                    ]
                ];
            } elseif ($index < 5) {
                // Productos 4-5: inventario normal + algunos caducados
                $inventarios = [
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(10, 25),
                        'fecha_caducidad' => Carbon::now()->addDays(rand(20, 60)),
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-1',
                        'notas' => 'Inventario actual'
                    ],
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(3, 8),
                        'fecha_caducidad' => Carbon::now()->subDays(rand(2, 10)), // CADUCADOS
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-2',
                        'notas' => 'Lote caducado - revisar para descarte'
                    ]
                ];
            } else {
                // Últimos productos: mayormente caducados
                $inventarios = [
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(8, 15),
                        'fecha_caducidad' => Carbon::now()->subDays(rand(5, 20)), // CADUCADOS
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-1',
                        'notas' => 'Lote caducado hace varios días'
                    ],
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(2, 6),
                        'fecha_caducidad' => Carbon::now()->subDays(rand(1, 3)), // RECIÉN CADUCADOS
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-2',
                        'notas' => 'Recién caducado'
                    ],
                    [
                        'producto_id' => $producto->id,
                        'cantidad' => rand(3, 8),
                        'fecha_caducidad' => Carbon::now()->addDays(rand(1, 2)), // PRÓXIMOS A CADUCAR
                        'lote' => 'LOTE-' . date('YmdHis') . '-' . $producto->id . '-3',
                        'notas' => 'Urge vender - caduca pronto'
                    ]
                ];
            }
            
            $stockTotal = 0;
            foreach ($inventarios as $inventarioData) {
                Inventario::create($inventarioData);
                $stockTotal += $inventarioData['cantidad'];
            }
            
            // Actualizar stock total del producto
            $producto->update(['stock' => $stockTotal]);
        }
    }
}