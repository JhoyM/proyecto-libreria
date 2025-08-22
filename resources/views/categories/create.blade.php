@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-ui.breadcrumbs :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Categorías', 'url' => route('categories.index')],
        ['label' => 'Nueva'],
    ]" />

    <x-ui.page-header title="Nueva Categoría" :icon="'<i class=\'fas fa-plus-circle\'></i>'">
        <x-slot name="actions">
            <a href="{{ route('categories.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="form-control w-full mb-4">
                            <label for="name" class="label">
                                <span class="label-text">Nombre <span class="text-error">*</span></span>
                            </label>
                            <label class="input input-bordered flex items-center gap-2">
                                <i class="fas fa-tag text-base-content/70"></i>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Libros de Texto" class="grow" required autofocus />
                            </label>
                            @error('name')
                                <div class="text-error text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                            @enderror
                            <div class="text-xs opacity-70 mt-1">El nombre debe ser único y descriptivo. Se usará para identificar la categoría en el sistema.</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label for="type" class="label">
                                    <span class="label-text">Tipo <span class="text-error">*</span></span>
                                </label>
                                <select id="type" name="type" class="select select-bordered w-full" required>
                                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>Seleccione un tipo</option>
                                    @foreach($categoryTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                    <input type="checkbox" id="status" name="status" value="1" class="toggle toggle-primary" {{ old('status', true) ? 'checked' : '' }} />
                                    <label for="status" class="cursor-pointer">
                                        <span class="badge {{ old('status', true) ? 'badge-success' : 'badge-ghost' }}">
                                            <i class="fas {{ old('status', true) ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ old('status', true) ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </label>
                                </div>
                                <div class="text-xs opacity-70 mt-1">Las categorías inactivas no estarán disponibles para nuevos productos.</div>
                                @error('status')
                                    <div class="text-error text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-control w-full mt-4">
                            <label for="description" class="label">
                                <span class="label-text">Descripción</span>
                            </label>
                            <textarea id="description" name="description" rows="4" maxlength="500" class="textarea textarea-bordered" placeholder="Ej: Libros de texto para educación primaria, secundaria y bachillerato">{{ old('description') }}</textarea>
                            <div class="text-xs opacity-70 mt-1 text-right" id="char-count">0/500</div>
                            @error('description')
                                <div class="text-error text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-base-200">
                            <a href="{{ route('categories.index') }}" class="btn btn-ghost">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                            </a>
                            <div class="join">
                                <button type="reset" class="btn btn-ghost join-item">
                                    <i class="fas fa-undo mr-2"></i> Restablecer
                                </button>
                                <button type="submit" class="btn btn-primary join-item">
                                    <i class="fas fa-save mr-2"></i> Guardar Categoría
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
                    <h3 class="card-title text-base-content/80"><i class="fas fa-info-circle mr-2"></i>Información</h3>
                    <p class="text-sm opacity-70">
                        <i class="fas fa-lightbulb text-warning mr-2"></i>
                        Las categorías le permiten organizar sus productos de manera lógica y jerárquica.
                    </p>
                    <ul class="text-sm opacity-80 list-disc ml-5">
                        <li class="mb-1"><b>Nombre:</b> Debe ser único y descriptivo.</li>
                        <li class="mb-1"><b>Tipo:</b> Seleccione la clasificación más adecuada.</li>
                        <li class="mb-1"><b>Estado:</b> Active o desactive según sea necesario.</li>
                        <li><b>Descripción:</b> Detalles adicionales opcionales.</li>
                    </ul>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const description = document.getElementById('description');
    const charCount = document.getElementById('char-count');
    const maxLength = 500;
    if (description && charCount) {
        const update = () => {
            const len = description.value.length;
            charCount.textContent = `${len}/${maxLength}`;
        };
        update();
        description.addEventListener('input', update);
    }
});
</script>
@endpush

@endsection
