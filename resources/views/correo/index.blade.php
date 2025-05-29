@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-envelope"></i> Sistema de Correos - Proveedores</h4>
                    <a href="{{ route('correo.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Correo
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        @foreach(\App\Models\Proveedor::with('productos')->get() as $proveedor)
                        <div class="col-md-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-building"></i> {{ $proveedor->nombre }}
                                    </h5>
                                    <small>{{ $proveedor->email }} | {{ $proveedor->telefono }}</small>
                                </div>
                                <div class="card-body">
                                    <p><strong>Contacto:</strong> {{ $proveedor->contacto }}</p>
                                    <p><strong>Ciudad:</strong> {{ $proveedor->ciudad }}</p>
                                    
                                    <h6 class="mt-3"><i class="fas fa-box"></i> Productos Asociados:</h6>
                                    @if($proveedor->productos->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Precio</th>
                                                        <th>Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($proveedor->productos as $producto)
                                                    <tr>
                                                        <td>{{ $producto->nombre }}</td>
                                                        <td>${{ number_format($producto->precio, 2) }}</td>
                                                        <td>{{ $producto->stock }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No hay productos asociados</p>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('correo.create') }}?proveedor={{ $proveedor->id }}" 
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-envelope"></i> Enviar Correo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection