<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\BarcodeApiController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PedidoController;

// Ruta principal
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard personalizado
    Route::get('/dashboard', function () {
        // Obtener estadísticas para el dashboard
        $totalProductos = \App\Models\Producto::count();
        
        $today = \Carbon\Carbon::now();
        $expiredCount = \App\Models\Inventario::where('fecha_caducidad', '<', $today)->count();
        
        $sevenDaysLater = \Carbon\Carbon::now()->addDays(7);
        $nearExpiryCount = \App\Models\Inventario::where('fecha_caducidad', '>=', $today)
                            ->where('fecha_caducidad', '<=', $sevenDaysLater)
                            ->count();
        
        // Productos con poco stock (menos de 10 unidades)
        $lowStockCount = \App\Models\Producto::where('stock', '<', 10)->count();
        
        return view('dashboard', compact('totalProductos', 'expiredCount', 'nearExpiryCount', 'lowStockCount'));
    })->name('dashboard');
    
    // Rutas solo para administradores
    Route::middleware(['role:admin'])->group(function () {
        // Gestión de usuarios
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuario.index');
        Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuario.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuario.store');
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuario.destroy');
        
        // Eliminar productos y proveedores (solo admin)
        Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('producto.destroy');
        Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedor.destroy');
        
        // Editar productos y proveedores (solo admin)
        Route::get('/productos/{producto}/editar', [ProductoController::class, 'edit'])->name('producto.edit');
        Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('producto.update');
        Route::get('/proveedores/{proveedor}/editar', [ProveedorController::class, 'edit'])->name('proveedor.edit');
        Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedor.update');
    });
    
    // Rutas de correos para ambos roles
    Route::get('/correos', [CorreoController::class, 'index'])->name('correo.index');
    Route::get('/correos/bandeja-entrada', [CorreoController::class, 'bandejadeEntrada'])->name('correo.bandeja-entrada');
    Route::get('/correos/enviados', [CorreoController::class, 'enviados'])->name('correo.enviados');
    Route::get('/correos/crear', [CorreoController::class, 'create'])->name('correo.create');
    Route::post('/correos', [CorreoController::class, 'store'])->name('correo.store');
    Route::get('/correos/{correo}', [CorreoController::class, 'show'])->name('correo.show');
    Route::patch('/correos/{correo}/marcar-leido', [CorreoController::class, 'marcarComoLeido'])->name('correo.marcar-leido');
    Route::delete('/correos/{correo}', [CorreoController::class, 'destroy'])->name('correo.destroy');
    
    // Rutas de pedidos para ambos roles
    Route::resource('pedido', PedidoController::class);
    
    // Rutas para despachadores y administradores
    Route::get('/productos', [ProductoController::class, 'index'])->name('producto.index');
    Route::get('/productos/crear', [ProductoController::class, 'create'])->name('producto.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('producto.store');
    Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('producto.show');
    Route::post('/productos/buscar', [ProductoController::class, 'buscarPorCodigo'])->name('producto.buscarPorCodigo');
    Route::post('/productos/detectar', [ProductoController::class, 'detectarProducto'])->name('producto.detectarProducto');
    
    // Rutas de proveedores (ver y crear)
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedor.index');
    Route::get('/proveedores/crear', [ProveedorController::class, 'create'])->name('proveedor.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedor.store');
    Route::get('/proveedores/{proveedor}', [ProveedorController::class, 'show'])->name('proveedor.show');
    
    // Rutas de inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/caducados', [InventarioController::class, 'caducados'])->name('inventario.caducados');
    Route::get('/inventario/proximos-caducar', [InventarioController::class, 'proximosCaducar'])->name('inventario.proximosCaducar');
    Route::delete('/inventario/{inventario}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
    
    // API de códigos de barras
    Route::post('/barcode/scan', [BarcodeApiController::class, 'scanBarcode'])->name('barcode.scan');
    
    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reporte.index');
    Route::get('/reportes/inventario', [ReporteController::class, 'inventario'])->name('reporte.inventario');
    Route::get('/reportes/productos', [ReporteController::class, 'productos'])->name('reporte.productos');
    Route::get('/reportes/caducados', [ReporteController::class, 'caducados'])->name('reporte.caducados');
    Route::get('/reportes/proximos-caducar', [ReporteController::class, 'proximosCaducar'])->name('reporte.proximosCaducar');
    Route::get('/reportes/existencias', [ReporteController::class, 'existencias'])->name('reportes.existencias');
    
    // Rutas de perfil
    Route::get('/profile', function () {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    })->name('profile.edit');
    
    Route::patch('/profile', function (Request $request) {
        $user = auth()->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado exitosamente.');
    })->name('profile.update');
    
    Route::put('/password', function (Request $request) {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('profile.edit')->with('success', 'Contraseña actualizada exitosamente.');
    })->name('password.update');
});