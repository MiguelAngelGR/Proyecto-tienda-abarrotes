<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Mostrar listado de productos
     */
    public function index()
    {
        $productos = Producto::with('proveedor')->paginate(10);
        return view('producto.index', compact('productos'));
    }
    
    /**
     * Mostrar formulario para crear producto
     */
    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('producto.create', compact('proveedores'));
    }
    
    /**
     * Guardar nuevo producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:50', // Permitir duplicados
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'proveedor_id' => 'required|exists:proveedores,id',
            'categoria' => 'nullable|string|max:100',
            'marca' => 'nullable|string|max:100',
            'peso' => 'nullable|numeric|min:0',
            'unidad_medida' => 'nullable|string|max:50',
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'required|date|after:today',
        ]);
        
        // Si existe un producto con el mismo código de barras, usar sus datos
        $productoExistente = null;
        if ($request->codigo_barras) {
            $productoExistente = Producto::where('codigo_barras', $request->codigo_barras)->first();
        }
        
        if ($productoExistente) {
            // Usar el producto existente
            $producto = $productoExistente;
            
            // Agregar al inventario agrupando por fecha de caducidad
            $inventarioExistente = Inventario::where('producto_id', $producto->id)
                ->where('fecha_caducidad', $request->fecha_caducidad)
                ->first();
                
            if ($inventarioExistente) {
                // Si ya existe un registro con la misma fecha, aumentar la cantidad
                $inventarioExistente->cantidad += $request->cantidad;
                $inventarioExistente->save();
            } else {
                // Crear nuevo registro de inventario
                Inventario::create([
                    'producto_id' => $producto->id,
                    'cantidad' => $request->cantidad,
                    'fecha_caducidad' => $request->fecha_caducidad,
                    'lote' => 'LOTE-' . date('YmdHis'),
                    'notas' => 'Entrada de productos existentes'
                ]);
            }
            
            // Actualizar stock total del producto
            $stockTotal = Inventario::where('producto_id', $producto->id)->sum('cantidad');
            $producto->update(['stock' => $stockTotal]);
            
        } else {
            // Crear nuevo producto
            $producto = Producto::create($request->except(['cantidad', 'fecha_caducidad']));
            
            // Crear registro de inventario
            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'fecha_caducidad' => $request->fecha_caducidad,
                'lote' => 'LOTE-' . date('YmdHis'),
                'notas' => 'Entrada inicial al crear el producto'
            ]);
            
            // Actualizar stock del producto
            $producto->update(['stock' => $request->cantidad]);
        }
        
        return redirect()->route('producto.index')
            ->with('success', 'Producto agregado correctamente al inventario.');
    }
    
    /**
     * Mostrar información de producto
     */
    public function show(Producto $producto)
    {
        $inventario = Inventario::where('producto_id', $producto->id)
            ->orderBy('fecha_caducidad', 'asc')
            ->get();
        return view('producto.show', compact('producto', 'inventario'));
    }
    
    /**
     * Mostrar formulario para editar producto
     */
    public function edit(Producto $producto)
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('producto.edit', compact('producto', 'proveedores'));
    }
    
    /**
     * Actualizar producto
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:50', // Sin unique constraint
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'proveedor_id' => 'required|exists:proveedores,id',
            'categoria' => 'nullable|string|max:100',
            'marca' => 'nullable|string|max:100',
            'peso' => 'nullable|numeric|min:0',
            'unidad_medida' => 'nullable|string|max:50',
        ]);
        
        $producto->update($request->all());
        
        // Recalcular stock total del producto
        $stockTotal = Inventario::where('producto_id', $producto->id)->sum('cantidad');
        $producto->update(['stock' => $stockTotal]);
        
        return redirect()->route('producto.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
    
    /**
     * Eliminar producto
     */
    public function destroy(Producto $producto)
    {
        // Eliminar todos los registros de inventario asociados
        Inventario::where('producto_id', $producto->id)->delete();
        
        // Eliminar el producto
        $producto->delete();
        
        return redirect()->route('producto.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
    
    /**
     * Buscar producto por código de barras (para autocompletar)
     */
    public function buscarPorCodigo(Request $request)
    {
        $request->validate([
            'codigo_barras' => 'required|string|max:50'
        ]);
        
        $producto = Producto::where('codigo_barras', $request->codigo_barras)->first();
        
        if ($producto) {
            return response()->json([
                'success' => true, 
                'producto' => [
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion,
                    'precio_compra' => $producto->precio_compra,
                    'precio_venta' => $producto->precio_venta,
                    'categoria' => $producto->categoria,
                    'marca' => $producto->marca,
                    'peso' => $producto->peso,
                    'unidad_medida' => $producto->unidad_medida,
                    'proveedor_id' => $producto->proveedor_id
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
        }
    }
    
    /**
     * Detectar producto usando la API de código de barras
     */
    public function detectarProducto(Request $request)
    {
        $request->validate([
            'codigo_barras' => 'required|string|max:50'
        ]);
        
        // Primero buscar en la base de datos local
        $producto = Producto::where('codigo_barras', $request->codigo_barras)->first();
        
        if ($producto) {
            return response()->json([
                'success' => true, 
                'producto' => $producto
            ]);
        } else {
            // Si el producto no existe localmente, aquí se podría implementar
            // la integración con una API externa de códigos de barras
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en base de datos local'
            ]);
        }
    }
    
    /**
     * Método para subir imágenes del producto
     */
    public function subirImagen(Request $request, Producto $producto)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($producto->imagen) {
            Storage::delete('public/productos/' . $producto->imagen);
        }
        
        $nombreArchivo = time() . '.' . $request->imagen->extension();
        
        // Guardar imagen
        $request->imagen->storeAs('public/productos', $nombreArchivo);
        
        $producto->imagen = $nombreArchivo;
        $producto->save();
        
        return response()->json([
            'success' => true,
            'imagen' => asset('storage/productos/' . $nombreArchivo)
        ]);
    }
    
    /**
     * Agregar stock existente (para productos ya creados)
     */
    public function agregarStock(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'required|date|after:today',
            'notas' => 'nullable|string|max:255'
        ]);
        
        // Verificar si ya existe inventario con la misma fecha de caducidad
        $inventarioExistente = Inventario::where('producto_id', $producto->id)
            ->where('fecha_caducidad', $request->fecha_caducidad)
            ->first();
            
        if ($inventarioExistente) {
            // Si ya existe, sumar la cantidad
            $inventarioExistente->cantidad += $request->cantidad;
            $inventarioExistente->save();
        } else {
            // Crear nuevo registro de inventario
            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'fecha_caducidad' => $request->fecha_caducidad,
                'lote' => 'LOTE-' . date('YmdHis'),
                'notas' => $request->notas ?? 'Entrada de stock adicional'
            ]);
        }
        
        // Actualizar stock total del producto
        $stockTotal = Inventario::where('producto_id', $producto->id)->sum('cantidad');
        $producto->update(['stock' => $stockTotal]);
        
        return redirect()->route('producto.show', $producto)
            ->with('success', 'Stock agregado correctamente al inventario.');
    }
    
    /**
     * Obtener productos con bajo stock
     */
    public function bajoStock($limite = 10)
    {
        $productos = Producto::where('stock', '<', $limite)
            ->with('proveedor')
            ->orderBy('stock', 'asc')
            ->get();
            
        return view('producto.bajo_stock', compact('productos', 'limite'));
    }
}