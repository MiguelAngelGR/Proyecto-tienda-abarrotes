@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Detalles del Proveedor</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('proveedor.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Información General</h5>
                    <hr>
                    <p><strong>Nombre:</strong> {{ $proveedor->nombre }}</p>
                    <p><strong>Persona de Contacto:</strong> {{ $proveedor->contacto ?? 'No especificado' }}</p>
                    <p><strong>Teléfono:</strong> {{ $proveedor->telefono ?? 'No especificado' }}</p>
                    <p><strong>Email:</strong> {{ $proveedor->email ?? 'No especificado' }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Dirección</h5>
                    <hr>
                    <p><strong>Dirección:</strong> {{ $proveedor->direccion ?? 'No especificada' }}</p>
                    <p><strong>Ciudad:</strong> {{ $proveedor->ciudad ?? 'No especificada' }}</p>
                    <p><strong>Estado:</strong> {{ $proveedor->estado ?? 'No especificado' }}</p>
                    <p><strong>Código Postal:</strong> {{ $proveedor->codigo_postal ?? 'No especificado' }}</p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="card-title">Notas Adicionales</h5>
                    <hr>
                    <p>{{ $proveedor->notas ?? 'Sin notas adicionales' }}</p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="card-title">Productos de este Proveedor</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proveedor->productos as $producto)
                                <tr>
                                    <td><a href="{{ route('producto.show', $producto) }}">{{ $producto->nombre }}</a></td>
                                    <td>{{ $producto->codigo_barras }}</td>
                                    <td>${{ number_format($producto->precio_compra, 2) }}</td>
                                    <td>${{ number_format($producto->precio_venta, 2) }}</td>
                                    <td>{{ $producto->stock }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Este proveedor no tiene productos registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('proveedor.edit', $proveedor) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Editar Proveedor
                </a>
                <form action="{{ route('proveedor.destroy', $proveedor) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
                        <i class="fas fa-trash"></i> Eliminar Proveedor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection