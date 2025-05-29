<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class BarcodeApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5000',
        ]);

        // Guardar la imagen temporalmente
        $image = $request->file('image');
        $imagePath = $image->store('temp', 'public');
        $fullPath = storage_path('app/public/' . $imagePath);

        try {
            // Para implementación local, usamos una simulación
            // En una implementación real, aquí iría la llamada a la API de códigos de barras
            // Como ejemplo, vamos a simular que detectamos un código de barras
            
            // Simulación - este código simula la detección de un código de barras
            // Este es un ejemplo y deberías reemplazarlo con una API real
            $barcode = '7501234567890'; // Código de prueba
            
            // Buscar producto con ese código de barras
            $product = Product::where('barcode', $barcode)->first();
            
            if ($product) {
                return response()->json([
                    'success' => true,
                    'product' => $product
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'barcode' => $barcode,
                    'message' => 'Código detectado pero producto no encontrado en la base de datos'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el código de barras: ' . $e->getMessage()
            ], 500);
        } finally {
            // Eliminar la imagen temporal
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    // Implementación alternativa usando una API real (ejemplo con Open Food Facts)
    public function scanBarcodeWithAPI(Request $request)
    {
        $barcode = $request->input('barcode');
        
        // Validar que sea un código de barras válido
        if (!$barcode || strlen($barcode) < 8) {
            return response()->json([
                'success' => false,
                'message' => 'Código de barras inválido'
            ], 400);
        }
        
        try {
            // Usar la API de Open Food Facts (gratuita)
            $response = file_get_contents("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");
            $data = json_decode($response, true);
            
            if ($data['status'] === 1) {
                // Producto encontrado en la API
                $productData = [
                    'barcode' => $barcode,
                    'name' => $data['product']['product_name'] ?? 'Producto sin nombre',
                    'description' => $data['product']['ingredients_text'] ?? '',
                    'image_url' => $data['product']['image_url'] ?? null,
                    'brand' => $data['product']['brands'] ?? '',
                    'quantity' => $data['product']['quantity'] ?? '',
                ];
                
                // Buscar si ya existe en nuestra base de datos
                $localProduct = Product::where('barcode', $barcode)->first();
                
                if ($localProduct) {
                    return response()->json([
                        'success' => true,
                        'product' => $localProduct,
                        'exists' => true
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'product_data' => $productData,
                        'exists' => false,
                        'message' => 'Producto encontrado en la API pero no en la base de datos local'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado en la base de datos de Open Food Facts'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al consultar la API: ' . $e->getMessage()
            ], 500);
        }
    }
}