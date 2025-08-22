@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-ui.breadcrumbs :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Categorías', 'url' => route('categories.index')],
        ['label' => 'Editar'],
    ]" />

    <x-ui.page-header title="Editar Categoría" :icon="'<i class=\'fas fa-edit\'></i>'">
        <x-slot name="actions">
            <a href="{{ route('categories.show', $category) }}" class="btn btn-ghost">
                <i class="fas fa-eye mr-2"></i> Ver Detalles
            </a>
        </x-slot>
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-control w-full mb-4">
                            <label for="name" class="label">
                                <span class="label-text">Nombre <span class="text-error">*</span></span>
                            </label>
                            <label class="input input-bordered flex items-center gap-2">
                                <i class="fas fa-tag text-base-content/70"></i>
                                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Ej: Libros de Texto" class="grow" required autofocus />
                            </label>
                            @error('name')
                                <div class="text-error text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                            @enderror
                            <div class="text-xs opacity-70 mt-1">El nombre debe ser único y descriptivo.</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label for="type" class="label">
                                    <span class="label-text">Tipo <span class="text-error">*</span></span>
                                </label>
                                <select id="type" name="type" class="select select-bordered w-full" required>
                                    <option value="" disabled>Seleccione un tipo</option>
                                    @foreach($categoryTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('type', $category->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="text-error text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text">Estado</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="status" name="status" value="1" class="toggle toggle-primary" {{ old('status', $category->status) ? 'checked' : '' }} />
                                    <label for="status" class="cursor-pointer">
                                        <span class="badge {{ old('status', $category->status) ? 'badge-success' : 'badge-ghost' }}">
                                            <i class="fas {{ old('status', $category->status) ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ old('status', $category->status) ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </label>
                                </div>
                                <div class="text-xs opacity-70 mt-1">Las categorías inactivas no estarán disponibles para nuevos productos.</div>
                            </div>
                        </div>

                        <div class="form-control w-full mt-4">
                            <label for="description" class="label">
                                <span class="label-text">Descripción</span>
                            </label>
                            <textarea id="description" name="description" rows="4" maxlength="500" class="textarea textarea-bordered" placeholder="Ej: Libros de texto para educación primaria, secundaria y bachillerato">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="text-error text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                            @enderror
                            <div class="text-xs opacity-70 mt-1">Máximo 500 caracteres.</div>
                        </div>

                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-base-200">
                            <a href="{{ route('categories.index') }}" class="btn btn-ghost">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                            <div class="join">
                                <button type="submit" class="btn btn-primary join-item">
                                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="card bg-base-100 shadow mb-6">
                <div class="card-body">
                    <h3 class="card-title text-base-content/80"><i class="fas fa-info-circle mr-2"></i>Información de la Categoría</h3>
                    <div class="grid grid-cols-1 gap-3 text-sm">
                        <div>
                            <div class="opacity-70">Creada</div>
                            <div><i class="far fa-calendar-alt mr-2"></i>{{ $category->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="opacity-70">Última Actualización</div>
                            <div><i class="far fa-clock mr-2"></i>{{ $category->updated_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge"><i class="fas fa-boxes mr-1"></i>{{ $category->products_count }}</span>
                            <span class="opacity-70">{{ Str::plural('producto', $category->products_count) }} en esta categoría</span>
                        </div>
                        @if($category->products_count > 0)
                        <div class="alert alert-warning text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Esta categoría tiene productos asociados. Los cambios pueden afectar a estos productos.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title text-base-content/80"><i class="fas fa-tags mr-2"></i>Tipos de Categorías</h3>
                    <ul class="menu bg-base-100 rounded-box">
                        @foreach($categoryTypes as $key => $label)
                            <li class="flex justify-between items-center px-2 py-1">
                                <span>{{ $label }}</span>
                                <span class="badge badge-ghost">{{ $key }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
