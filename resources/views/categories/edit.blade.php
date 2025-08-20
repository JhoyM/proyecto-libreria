@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-edit text-primary me-2"></i>Editar Categoría
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar: {{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Nombre <span class="text-danger">*</span>
                                <i class="fas fa-info-circle text-muted ms-1" 
                                   data-bs-toggle="tooltip" 
                                   title="Ingrese el nombre de la categoría. Este campo es obligatorio."></i>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-tag text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $category->name) }}" 
                                       placeholder="Ej: Libros de Texto" 
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text">
                                El nombre debe ser único y descriptivo. Se usará para identificar la categoría en el sistema.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="type" class="form-label">
                                        Tipo <span class="text-danger">*</span>
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           data-bs-toggle="tooltip" 
                                           title="Seleccione el tipo de categoría. Esto ayudará a organizar mejor sus productos."></i>
                                    </label>
                                    <select class="form-select form-select-lg @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="" disabled>Seleccione un tipo</option>
                                        @foreach($categoryTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('type', $category->type) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">
                                        Estado
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           data-bs-toggle="tooltip" 
                                           title="Active o desactive la categoría según sea necesario."></i>
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               role="switch" 
                                               id="status" 
                                               name="status" 
                                               value="1" 
                                               {{ old('status', $category->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            @if(old('status', $category->status))
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Activo
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times-circle me-1"></i> Inactivo
                                                </span>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Las categorías inactivas no estarán disponibles para nuevos productos.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Descripción
                                <i class="fas fa-info-circle text-muted ms-1" 
                                   data-bs-toggle="tooltip" 
                                   title="Proporcione una descripción detallada de la categoría (opcional)."></i>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Ej: Libros de texto para educación primaria, secundaria y bachillerato">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                Máximo 500 caracteres. Esta descripción ayudará a los usuarios a entender mejor la categoría.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye me-1"></i> Ver Detalles
                            </a>
                            <div class="btn-group" role="group">
                                <a href="{{ route('categories.index') }}" class="btn btn-light">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información de la Categoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="mb-1">Creada</h6>
                        <p class="text-muted small mb-0">
                            <i class="far fa-calendar-alt me-2"></i> {{ $category->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="mb-1">Última Actualización</h6>
                        <p class="text-muted small mb-0">
                            <i class="far fa-clock me-2"></i> {{ $category->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="mb-1">Productos Asociados</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-2">
                                {{ $category->products_count }}
                            </span>
                            <span class="small">
                                {{ Str::plural('producto', $category->products_count) }} en esta categoría
                            </span>
                        </div>
                    </div>
                    
                    @if($category->products_count > 0)
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta categoría tiene productos asociados. Los cambios pueden afectar a estos productos.
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-tags me-2"></i>Tipos de Categorías
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        @foreach($categoryTypes as $key => $label)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ $label }}</span>
                                <span class="badge bg-soft-primary rounded-pill">{{ $key }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1rem;
    }
    .form-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        font-weight: 600;
        padding: 1rem 1.25rem;
    }
    .btn {
        font-weight: 500;
        border-radius: 0.375rem;
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
        
        // Toggle del estado
        const statusSwitch = document.getElementById('status');
        const statusLabel = statusSwitch.nextElementSibling;
        
        statusSwitch.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.innerHTML = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Activo</span>';
            } else {
                statusLabel.innerHTML = '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> Inactivo</span>';
            }
        });
        
        // Validación de formulario
        (function () {
            'use strict'
            
            var forms = document.querySelectorAll('.needs-validation')
            
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    });
</script>
@endpush

@endsection
