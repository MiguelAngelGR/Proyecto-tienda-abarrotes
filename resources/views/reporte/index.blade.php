@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Generación de Reportes</h2>
            <p class="text-muted">Genera reportes en PDF de diferentes aspectos del inventario</p>
        </div>
    </div>

    <div class="row">
        <!-- Reporte de Inventario General -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-primary mb-3">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h5 class="card-title">Inventario General</h5>
                    <p class="card-text">Reporte completo de todos los productos en inventario con sus cantidades y fechas de caducidad.</p>
                    <a href="{{ route('reporte.inventario') }}" class="btn btn-primary">
                        <i class="fas fa-file-pdf me-2"></i>Generar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Productos -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-success mb-3">
                        <i class="fas fa-box"></i>
                    </div>
                    <h5 class="card-title">Catálogo de Productos</h5>
                    <p class="card-text">Lista completa de productos registrados con información de precios y proveedores.</p>
                    <a href="{{ route('reporte.productos') }}" class="btn btn-success">
                        <i class="fas fa-file-pdf me-2"></i>Generar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Productos Caducados -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-danger mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5 class="card-title">Productos Caducados</h5>
                    <p class="card-text">Reporte de todos los productos que han superado su fecha de caducidad.</p>
                    <a href="{{ route('reporte.caducados') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Generar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Próximos a Caducar -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-warning mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5 class="card-title">Próximos a Caducar</h5>
                    <p class="card-text">Productos que caducarán en los próximos 7 días y requieren atención urgente.</p>
                    <a href="{{ route('reporte.proximosCaducar') }}" class="btn btn-warning text-white">
                        <i class="fas fa-file-pdf me-2"></i>Generar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Información de Reportes</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-info-circle text-info me-2"></i>Los reportes se generan con fecha actual</span>
                            <span class="badge bg-info rounded-pill">{{ now()->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-download text-primary me-2"></i>Formato de descarga</span>
                            <span class="badge bg-primary rounded-pill">PDF</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock text-secondary me-2"></i>Datos actualizados al momento</span>
                            <span class="badge bg-secondary rounded-pill">Tiempo real</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection