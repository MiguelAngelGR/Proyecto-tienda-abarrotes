@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Crear Nuevo Producto</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('producto.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras') }}">
                                <button type="button" class="btn btn-secondary" id="scanButton">
                                    <i class="fas fa-barcode"></i> Escanear
                                </button>
                                <button type="button" class="btn btn-info" id="searchButton">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                            @error('codigo_barras')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Ingresa un código para cargar datos automáticamente si ya existe</small>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio_compra" class="form-label">Precio de Compra *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control @error('precio_compra') is-invalid @enderror" id="precio_compra" name="precio_compra" value="{{ old('precio_compra') }}" required>
                                    </div>
                                    @error('precio_compra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio_venta" class="form-label">Precio de Venta *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control @error('precio_venta') is-invalid @enderror" id="precio_venta" name="precio_venta" value="{{ old('precio_venta') }}" required>
                                    </div>
                                    @error('precio_venta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <input type="text" class="form-control @error('categoria') is-invalid @enderror" id="categoria" name="categoria" value="{{ old('categoria') }}">
                                    @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control @error('marca') is-invalid @enderror" id="marca" name="marca" value="{{ old('marca') }}">
                                    @error('marca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peso" class="form-label">Peso</label>
                                    <input type="number" step="0.01" class="form-control @error('peso') is-invalid @enderror" id="peso" name="peso" value="{{ old('peso') }}">
                                    @error('peso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                                    <input type="text" class="form-control @error('unidad_medida') is-invalid @enderror" id="unidad_medida" name="unidad_medida" value="{{ old('unidad_medida') }}">
                                    @error('unidad_medida')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="proveedor_id" class="form-label">Proveedor *</label>
                            <select class="form-control @error('proveedor_id') is-invalid @enderror" id="proveedor_id" name="proveedor_id" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Campos para el inventario -->
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad a agregar al inventario *</label>
                            <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required>
                            @error('cantidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Indica cuántas unidades de este producto deseas agregar al inventario.</small>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_caducidad" class="form-label">Fecha de caducidad *</label>
                            <input type="date" class="form-control @error('fecha_caducidad') is-invalid @enderror" id="fecha_caducidad" name="fecha_caducidad" value="{{ old('fecha_caducidad') }}" required>
                            @error('fecha_caducidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Producto</button>
                            <a href="{{ route('producto.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codigoBarrasInput = document.getElementById('codigo_barras');
    const searchButton = document.getElementById('searchButton');
    
    // Función para buscar producto por código de barras
    function buscarProducto() {
        const codigoBarras = codigoBarrasInput.value.trim();
        
        if (!codigoBarras) {
            alert('Por favor ingrese un código de barras');
            return;
        }
        
        fetch('{{ route("producto.buscarPorCodigo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                codigo_barras: codigoBarras
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Llenar automáticamente los campos
                document.getElementById('nombre').value = data.producto.nombre || '';
                document.getElementById('descripcion').value = data.producto.descripcion || '';
                document.getElementById('precio_compra').value = data.producto.precio_compra || '';
                document.getElementById('precio_venta').value = data.producto.precio_venta || '';
                document.getElementById('categoria').value = data.producto.categoria || '';
                document.getElementById('marca').value = data.producto.marca || '';
                document.getElementById('peso').value = data.producto.peso || '';
                document.getElementById('unidad_medida').value = data.producto.unidad_medida || '';
                document.getElementById('proveedor_id').value = data.producto.proveedor_id || '';
                
                alert('Datos del producto cargados automáticamente. Solo ajusta la cantidad y fecha de caducidad.');
            } else {
                alert('Producto no encontrado. Puedes crear uno nuevo con este código.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al buscar el producto');
        });
    }
    
    // Evento click del botón buscar
    searchButton.addEventListener('click', buscarProducto);
    
    // Evento enter en el campo de código de barras
    codigoBarrasInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarProducto();
        }
    });
});
</script>
@endsection