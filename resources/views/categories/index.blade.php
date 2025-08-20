@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-tags text-primary"></i> Gestión de Categorías
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categorías</li>
                </ol>
            </nav>
        </div>
        @can('categories.create')
        <div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Nueva Categoría
            </a>
        </div>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <!-- Filtros -->
            <div class="p-4 border-bottom">
                <form action="{{ route('categories.index') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="q" class="form-control" 
                                   placeholder="Buscar por nombre o descripción..." 
                                   value="{{ $q ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="">Todos los tipos</option>
                            @foreach($typeFilters as $key => $label)
                                <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de categorías -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Tipo</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light text-primary rounded-circle font-size-18">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                            <p class="text-muted mb-0 small">{{ $category->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'libros' => 'primary',
                                            'utiles_escolares' => 'success',
                                            'oficina' => 'info'
                                        ];
                                        $color = $typeColors[$category->type] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-soft-{{ $color }} text-{{ $color }}">
                                        <i class="fas {{ [
                                            'libros' => 'fa-book',
                                            'utiles_escolares' => 'fa-pencil-ruler',
                                            'oficina' => 'fa-briefcase'
                                        ][$category->type] ?? 'fa-tag' }} me-1"></i>
                                        {{ $typeFilters[$category->type] ?? $category->type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-boxes me-1"></i>
                                        {{ $category->products_count }} {{ Str::plural('producto', $category->products_count) }}
                                    </span>
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="badge bg-success bg-soft">
                                            <i class="fas fa-check-circle me-1"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-soft">
                                            <i class="fas fa-times-circle me-1"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('categories.show', $category) }}" 
                                           class="btn btn-sm btn-soft-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('categories.update')
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="btn btn-sm btn-soft-warning" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('categories.delete')
                                        <form action="{{ route('categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?\n\nNota: Solo se pueden eliminar categorías sin productos asociados.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-soft-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Eliminar"
                                                    {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="fas fa-inbox fa-2x"></i>
                                            </div>
                                        </div>
                                        <h5>No se encontraron categorías</h5>
                                        @if(request()->has('q') || request()->has('type'))
                                            <p class="text-muted">No hay resultados para tu búsqueda. Intenta con otros términos o</p>
                                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-times me-1"></i> Limpiar filtros
                                            </a>
                                        @else
                                            <p class="text-muted">Aún no hay categorías registradas. Comienza creando una nueva.</p>
                                            @can('categories.create')
                                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Crear Categoría
                                            </a>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($categories->hasPages())
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Mostrando <b>{{ $categories->firstItem() }}</b> a <b>{{ $categories->lastItem() }}</b> de <b>{{ $categories->total() }}</b> categorías
                    </div>
                    {{ $categories->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

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
