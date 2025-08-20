@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-tag text-primary me-2"></i>{{ $category->name }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            @can('categories.update')
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>Información de la Categoría
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted text-uppercase small mb-2">Nombre</h6>
                                <p class="h5 mb-0">{{ $category->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted text-uppercase small mb-2">Tipo</h6>
                                {!! $category->type_badge !!}
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted text-uppercase small mb-2">Estado</h6>
                                {!! $category->status_badge !!}
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted text-uppercase small mb-2">Creada</h6>
                                <p class="mb-0">
                                    <i class="far fa-calendar-alt me-2 text-muted"></i>
                                    {{ $category->created_at->format('d/m/Y H:i') }}
                                    <small class="d-block text-muted mt-1">
                                        ({{ $category->created_at->diffForHumans() }})
                                    </small>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted text-uppercase small mb-2">Última Actualización</h6>
                                <p class="mb-0">
                                    <i class="far fa-clock me-2 text-muted"></i>
                                    {{ $category->updated_at->format('d/m/Y H:i') }}
                                    <small class="d-block text-muted mt-1">
                                        ({{ $category->updated_at->diffForHumans() }})
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($category->description)
                    <div class="border-top pt-4 mt-3">
                        <h6 class="text-muted text-uppercase small mb-3">Descripción</h6>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($category->description)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Productos de esta categoría -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes text-primary me-2"></i>
                        Productos en esta categoría
                        <span class="badge bg-primary rounded-pill ms-2">{{ $products->total() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-end">Precio</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="rounded me-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                    <small class="text-muted">Código: {{ $product->code ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($product->price, 2) }} Bs.
                                        </td>
                                        <td class="text-center">
                                            @if($product->stock <= $product->stock_alert)
                                                <span class="badge bg-danger">{{ $product->stock }} unidades</span>
                                            @else
                                                <span class="badge bg-success">{{ $product->stock }} unidades</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($products->hasPages())
                        <div class="p-3 border-top">
                            {{ $products->links() }}
                        </div>
                        @endif
                        
                    @else
                    <div class="text-center p-5">
                        <div class="mb-3">
                            <i class="fas fa-box-open fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-3">No hay productos en esta categoría</h5>
                        @can('products.create')
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Agregar Producto
                        </a>
                        @endcan
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Resumen -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Resumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total de Productos</span>
                        <span class="fw-bold">{{ $category->products_count }}</span>
                    </div>
                    
                    <div class="progress mb-4" style="height: 8px;">
                        @php
                            $percentage = $category->products_count > 0 
                                ? min(100, ($category->products_count / 100) * 100) 
                                : 0;
                        @endphp
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ $percentage }}%" 
                             aria-valuenow="{{ $category->products_count }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    
                    @if($category->type === 'libros')
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Esta categoría está marcada como <strong>Libros</strong>. Los productos aquí podrían incluir libros de texto, novelas, etc.
                    </div>
                    @elseif($category->type === 'utiles_escolares')
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-pencil-alt me-2"></i>
                        Esta categoría está marcada como <strong>Útiles Escolares</strong>. Incluye materiales como cuadernos, lápices, etc.
                    </div>
                    @elseif($category->type === 'oficina')
                    <div class="alert alert-secondary small mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        Esta categoría está marcada como <strong>Oficina</strong>. Incluye suministros de oficina varios.
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Acciones rápidas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @can('products.create')
                        <a href="{{ route('products.create', ['category_id' => $category->id]) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-plus-circle text-success me-2"></i>Agregar Producto</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                        @endcan
                        
                        @can('categories.products.export')
                        <a href="#" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-export text-info me-2"></i>Exportar Productos</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                        @endcan
                        
                        @can('categories.update')
                        <a href="{{ route('categories.edit', $category) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-edit text-warning me-2"></i>Editar Categoría</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                        @endcan
                        
                        @can('categories.delete')
                        @if($category->products_count === 0)
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" 
                              class="list-group-item list-group-item-action p-0"
                              onsubmit="return confirm('¿Está seguro de eliminar esta categoría? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-decoration-none text-danger w-100 text-start">
                                <i class="fas fa-trash-alt me-2"></i>Eliminar Categoría
                            </button>
                        </form>
                        @else
                        <div class="list-group-item text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No se puede eliminar porque tiene productos asociados.
                        </div>
                        @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05) !important;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
        padding: 0.75rem 1.25rem;
    }
    .list-group-item:first-child {
        border-top: 0;
    }
    .list-group-item:last-child {
        border-bottom: 0;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .progress {
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
