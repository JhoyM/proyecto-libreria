@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle text-primary me-2"></i>Nueva Categoría
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nueva</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('categories.store') }}" method="POST" class="needs-validation" novalidate>
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <!-- Name Field -->
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
                           value="{{ old('name') }}" 
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
                <!-- Type Field -->
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
                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Seleccione un tipo</option>
                            @foreach($categoryTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
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
                
                <!-- Status Field -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label">
                            Estado
                            <i class="fas fa-info-circle text-muted ms-1" 
                               data-bs-toggle="tooltip" 
                               title="Active o desactive la categoría según sea necesario."></i>
                        </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('status') is-invalid @enderror" 
                                   type="checkbox" 
                                   role="switch" 
                                   id="status" 
                                   name="status" 
                                   value="1" 
                                   {{ old('status', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Activo
                                </span>
                            </label>
                        </div>
                        @error('status')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            Las categorías inactivas no estarán disponibles para nuevos productos.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Field -->
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
                          maxlength="500"
                          placeholder="Ej: Libros de texto para educación primaria, secundaria y bachillerato">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                    </div>
                @enderror
                <div class="form-text text-end">
                    <span id="char-count">0</span>/500 caracteres
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
                <div class="btn-group" role="group">
                    <button type="reset" class="btn btn-light">
                        <i class="fas fa-undo me-1"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Guardar Categoría
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
                        <i class="fas fa-info-circle me-2"></i>Información
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        <i class="fas fa-lightbulb text-warning me-2"></i> 
                        Las categorías le permiten organizar sus productos de manera lógica y jerárquica.
                    </p>
                    <ul class="small text-muted ps-3">
                        <li class="mb-2">
                            <strong>Nombre:</strong> Debe ser único y descriptivo.
                        </li>
                        <li class="mb-2">
                            <strong>Tipo:</strong> Seleccione la clasificación más adecuada para la categoría.
                        </li>
                        <li class="mb-2">
                            <strong>Estado:</strong> Active o desactive según sea necesario.
                        </li>
                        <li>
                            <strong>Descripción:</strong> Proporcione detalles adicionales si es necesario.
                        </li>
                    </ul>
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
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-check-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-switch .form-check-input:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%2386b7fe'/%3e%3c/svg%3e") !important;
    }
    
    .form-switch .form-check-input:checked:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
    }
    
    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .card-header {
        font-weight: 600;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.75rem 1.25rem;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .form-switch {
        padding-left: 2.5em;
    }
    
    .form-switch .form-check-input {
        width: 2em;
        margin-left: -2.5em;
        height: 1.25em;
        margin-top: 0.15em;
    }
    
    .form-switch .form-check-input:checked {
        background-position: right center;
    }
    
    .form-switch .form-check-input:focus {
        background-position: right center;
    }
    
    .form-switch .form-check-input:checked:focus {
        background-position: right center;
    }
    
    /* Custom styles for character counter */
    .char-counter {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: right;
        margin-top: 0.25rem;
    }
    
    .char-counter.near-limit {
        color: #fd7e14;
        font-weight: 500;
    }
    
    .char-counter.over-limit {
        color: #dc3545;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Character counter for description textarea
        const description = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        const maxLength = 500;
        const warningThreshold = 50; // Show warning when X characters left
        
        if (description && charCount) {
            // Initial count
            updateCharCount();
            
            // Update on input
            description.addEventListener('input', updateCharCount);
            
            function updateCharCount() {
                const currentLength = description.value.length;
                const remaining = maxLength - currentLength;
                
                charCount.textContent = `${currentLength}/${maxLength}`;
                charCount.className = '';
                
                if (remaining <= 0) {
                    charCount.classList.add('text-danger', 'fw-bold');
                    description.classList.add('is-invalid');
                } else if (remaining <= warningThreshold) {
                    charCount.classList.add('text-warning', 'fw-semibold');
                    description.classList.remove('is-invalid');
                } else {
                    charCount.classList.add('text-muted');
                    description.classList.remove('is-invalid');
                }
            }
        }
        
        // Form validation
        const form = document.querySelector('.needs-validation');
        if (form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        }
        
        // Toggle status badge text and color
        const statusCheckbox = document.getElementById('status');
        const statusBadge = document.querySelector('.form-check-label .badge');
        
        if (statusCheckbox && statusBadge) {
            statusCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Activo';
                } else {
                    statusBadge.className = 'badge bg-secondary';
                    statusBadge.innerHTML = '<i class="fas fa-times-circle me-1"></i> Inactivo';
                }
            });
        }
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
