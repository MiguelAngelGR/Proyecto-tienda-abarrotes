@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Gestión de Inventario</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('producto.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Agregar Producto
            </a>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Fecha Caducidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventario as $item)
                        <tr>
                            <td>{{ $item->producto->nombre }}</td>
                            <td>{{ $item->lote }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->estaCaducado())
                                <span class="badge bg-danger">Caducado</span>
                                @elseif($item->proximoACaducar())
                                <span class="badge bg-warning">Próximo a caducar</span>
                                @else
                                <span class="badge bg-success">Vigente</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('inventario.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay registros en el inventario</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $inventario->links() }}
            </div>
        </div>
    </div>
</div>
@endsection