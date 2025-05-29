@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Productos Próximos a Caducar</h2>
            <p class="text-muted">Listado de productos que caducarán en los próximos 7 días ({{ $today->format('d/m/Y') }} - {{ $sevenDaysLater->format('d/m/Y') }})</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('reporte.proximosCaducar') }}" class="btn btn-info text-white">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </a>
        </div>
    </div>
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
                            <th>Días restantes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventarioProximoCaducar as $item)
                        <tr class="table-warning">
                            <td>{{ $item->producto->nombre }}</td>
                            <td>{{ $item->lote }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                            <td>{{ $today->diffInDays($item->fecha_caducidad) }} días</td>
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
                            <td colspan="6" class="text-center">No hay productos próximos a caducar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $inventarioProximoCaducar->links() }}
            </div>
        </div>
    </div>
</div>
@endsection