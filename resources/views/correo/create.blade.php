@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-envelope-open"></i> Crear Nuevo Correo</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('correo.store') }}">
                @csrf

                @if(auth()->user()->role === 'admin')
                <!-- Tipo de Destinatario -->
                <div class="mb-3">
                    <label class="form-label"><strong>Tipo de Destinatario</strong></label>
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_destinatario" value="interno" id="interno" checked>
                            <label class="form-check-label" for="interno">
                                Usuario Interno (Despachadores)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_destinatario" value="externo" id="externo">
                            <label class="form-check-label" for="externo">
                                Proveedor Externo
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Destinatario Interno -->
                <div id="destinatario-interno" class="mb-3">
                    <label for="destinatario_id" class="form-label">Seleccionar Usuario</label>
                    <select name="destinatario_id" id="destinatario_id" class="form-select">
                        <option value="">Seleccione un usuario...</option>
                        @foreach(\App\Models\User::where('role', 'dispatcher')->get() as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Proveedor Externo -->
                <div id="proveedor-externo" class="mb-3" style="display: none;">
                    <label for="proveedor_id" class="form-label">Seleccionar Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-select">
                        <option value="">Seleccione un proveedor...</option>
                        @foreach(\App\Models\Proveedor::orderBy('nombre')->get() as $proveedor)
                            <option value="{{ $proveedor->id }}" 
                                    {{ request('proveedor') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }} ({{ $proveedor->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Productos del Proveedor -->
                <div id="productos-proveedor" class="mb-3" style="display: none;">
                    <label class="form-label">Productos a Solicitar</label>
                    <div id="productos-list">
                        <!-- Se llenará dinámicamente -->
                    </div>
                </div>

                <!-- Fecha de Entrega -->
                <div id="fecha-entrega" class="mb-3" style="display: none;">
                    <label for="fecha_entrega" class="form-label">Fecha de Entrega Solicitada</label>
                    <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
                </div>

                <!-- Ubicación de Entrega -->
                <div id="ubicacion-entrega" class="mb-3" style="display: none;">
                    <label for="ubicacion_entrega" class="form-label">Ubicación de Entrega</label>
                    <input type="text" name="ubicacion_entrega" id="ubicacion_entrega" class="form-control" 
                           placeholder="Dirección donde entregar los productos">
                </div>

                @else
                <!-- Solo despachadores -->
                <div class="mb-3">
                    <label for="destinatario_id" class="form-label">Seleccionar Administrador</label>
                    <select name="destinatario_id" id="destinatario_id" class="form-select" required>
                        <option value="">Seleccione un administrador...</option>
                        @foreach(\App\Models\User::where('role', 'admin')->get() as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Asunto -->
                <div class="mb-3">
                    <label for="asunto" class="form-label">Asunto</label>
                    <input type="text" name="asunto" id="asunto" class="form-control" required value="{{ old('asunto') }}">
                </div>

                <!-- Contenido -->
                <div class="mb-3">
                    <label for="contenido" class="form-label">Mensaje</label>
                    <textarea name="contenido" id="contenido" rows="6" class="form-control" required>{{ old('contenido') }}</textarea>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('correo.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Correo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="tipo_destinatario"]');
    const internoDiv = document.getElementById('destinatario-interno');
    const externoDiv = document.getElementById('proveedor-externo');
    const productosDiv = document.getElementById('productos-proveedor');
    const fechaDiv = document.getElementById('fecha-entrega');
    const ubicacionDiv = document.getElementById('ubicacion-entrega');
    const proveedorSelect = document.getElementById('proveedor_id');

    // Si viene con proveedor preseleccionado
    if (document.querySelector('input[name="tipo_destinatario"][value="externo"]') && proveedorSelect.value) {
        document.querySelector('input[name="tipo_destinatario"][value="externo"]').checked = true;
        toggleDestination();
    }

    radios.forEach(radio => {
        radio.addEventListener('change', toggleDestination);
    });

    proveedorSelect.addEventListener('change', function() {
        if (this.value) {
            loadProductos(this.value);
            productosDiv.style.display = 'block';
            fechaDiv.style.display = 'block';
            ubicacionDiv.style.display = 'block';
        } else {
            productosDiv.style.display = 'none';
            fechaDiv.style.display = 'none';
            ubicacionDiv.style.display = 'none';
        }
    });

    function toggleDestination() {
        const selectedValue = document.querySelector('input[name="tipo_destinatario"]:checked').value;
        
        if (selectedValue === 'interno') {
            internoDiv.style.display = 'block';
            externoDiv.style.display = 'none';
            productosDiv.style.display = 'none';
            fechaDiv.style.display = 'none';
            ubicacionDiv.style.display = 'none';
            proveedorSelect.value = '';
        } else {
            internoDiv.style.display = 'none';
            externoDiv.style.display = 'block';
            document.getElementById('destinatario_id').value = '';
            
            if (proveedorSelect.value) {
                productosDiv.style.display = 'block';
                fechaDiv.style.display = 'block';
                ubicacionDiv.style.display = 'block';
            }
        }
    }

    function loadProductos(proveedorId) {
        // Aquí cargarías los productos del proveedor vía AJAX
        const productosList = document.getElementById('productos-list');
        productosList.innerHTML = `
            <div class="mb-2">
                <input type="checkbox" name="productos[]" value="1" class="form-check-input me-2">
                <label>Producto ejemplo - Cantidad: </label>
                <input type="number" name="cantidades[]" class="form-control d-inline-block ms-2" style="width: 100px;" min="1">
            </div>
        `;
    }
});
</script>
@endsection