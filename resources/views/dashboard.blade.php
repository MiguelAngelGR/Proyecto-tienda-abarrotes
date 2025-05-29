@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="display-5 fw-bold">Panel de Control</h2>
            <p class="text-muted">
                Bienvenido/a {{ Auth::user()->name }} 
                <span class="badge {{ Auth::user()->role === 'administrador' ? 'bg-danger' : 'bg-success' }}">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </p>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="row">
        <!-- Tarjeta de Productos Totales -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-primary mb-2">
                        <i class="fas fa-box"></i>
                    </div>
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text display-5 fw-bold">{{ $totalProductos ?? 0 }}</p>
                    <a href="{{ route('producto.index') }}" class="btn btn-sm btn-outline-primary">Ver productos</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Productos Caducados -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-danger mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5 class="card-title">Productos Caducados</h5>
                    <p class="card-text display-5 fw-bold">{{ $expiredCount ?? 0 }}</p>
                    <a href="{{ route('inventario.caducados') }}" class="btn btn-sm btn-outline-danger">Ver caducados</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Próximos a Caducar -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-warning mb-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5 class="card-title">Próximos a Caducar</h5>
                    <p class="card-text display-5 fw-bold">{{ $nearExpiryCount ?? 0 }}</p>
                    <a href="{{ route('inventario.proximosCaducar') }}" class="btn btn-sm btn-outline-warning">Ver próximos</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Poco Stock -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 text-info mb-2">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h5 class="card-title">Poco Stock</h5>
                    <p class="card-text display-5 fw-bold">{{ $lowStockCount ?? 0 }}</p>
                    <a href="{{ route('producto.index') }}?stock=low" class="btn btn-sm btn-outline-info">Ver poco stock</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones según el rol -->
    @if(auth()->user()->role === 'administrador')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-crown me-2"></i>Panel de Administrador</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('correo.index') }}" class="btn btn-outline-primary w-100 p-3">
                                <i class="fas fa-envelope-open-text fa-2x mb-2 d-block"></i>
                                <strong>Correos a Proveedores</strong>
                                <small class="d-block text-muted">Comunicación comercial</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('usuario.index') }}" class="btn btn-outline-success w-100 p-3">
                                <i class="fas fa-users-cog fa-2x mb-2 d-block"></i>
                                <strong>Gestionar Usuarios</strong>
                                <small class="d-block text-muted">Crear y administrar cuentas</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('producto.create') }}" class="btn btn-outline-info w-100 p-3">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                <strong>Nuevo Producto</strong>
                                <small class="d-block text-muted">Agregar al catálogo</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('proveedor.create') }}" class="btn btn-outline-warning w-100 p-3">
                                <i class="fas fa-truck fa-2x mb-2 d-block"></i>
                                <strong>Nuevo Proveedor</strong>
                                <small class="d-block text-muted">Registrar proveedor</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Panel de Despachador</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Como despachador, puedes gestionar productos, inventario y generar reportes operativos.
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('producto.create') }}" class="btn btn-outline-primary w-100 p-3">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                <strong>Agregar Producto</strong>
                                <small class="d-block text-muted">Nuevo producto al inventario</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('inventario.index') }}" class="btn btn-outline-success w-100 p-3">
                                <i class="fas fa-warehouse fa-2x mb-2 d-block"></i>
                                <strong>Gestionar Inventario</strong>
                                <small class="d-block text-muted">Control de stock</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('reporte.index') }}" class="btn btn-outline-info w-100 p-3">
                                <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                <strong>Reportes</strong>
                                <small class="d-block text-muted">Generar informes</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Acciones comunes para ambos roles -->
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventario.caducados') }}" class="btn btn-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i> Ver Productos Caducados
                        </a>
                        <a href="{{ route('inventario.proximosCaducar') }}" class="btn btn-warning text-white">
                            <i class="fas fa-clock me-2"></i> Próximos a Caducar
                        </a>
                        <a href="{{ route('reporte.index') }}" class="btn btn-info text-white">
                            <i class="fas fa-chart-bar me-2"></i> Generar Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Versión de Laravel
                            <span class="badge bg-primary rounded-pill">{{ app()->version() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Fecha
                            <span class="badge bg-secondary rounded-pill">{{ now()->format('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Usuario
                            <span class="badge bg-success rounded-pill">{{ Auth::user()->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Rol
                            <span class="badge {{ Auth::user()->role === 'administrador' ? 'bg-danger' : 'bg-success' }} rounded-pill">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas importantes si hay productos críticos -->
    @if($expiredCount > 0 || $nearExpiryCount > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Alertas Importantes</h5>
                </div>
                <div class="card-body">
                    @if($expiredCount > 0)
                    <div class="alert alert-danger border-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ $expiredCount }} producto(s) caducado(s)</strong> requieren atención inmediata.
                        <a href="{{ route('inventario.caducados') }}" class="alert-link">Ver detalles</a>
                    </div>
                    @endif
                    
                    @if($nearExpiryCount > 0)
                    <div class="alert alert-warning border-0">
                        <i class="fas fa-clock me-2"></i>
                        <strong>{{ $nearExpiryCount }} producto(s) próximos a caducar</strong> en los próximos 7 días.
                        <a href="{{ route('inventario.proximosCaducar') }}" class="alert-link">Ver detalles</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection