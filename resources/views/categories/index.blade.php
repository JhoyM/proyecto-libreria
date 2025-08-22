@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-ui.breadcrumbs :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Categorías'],
    ]" />
    <x-ui.page-header title="Gestión de Categorías"
        :icon="'<i class=\'fas fa-tags\'></i>'">
        <x-slot name="actions">
            @can('categories.create')
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-2"></i> Nueva Categoría
            </a>
            @endcan
        </x-slot>
    </x-ui.page-header>

    @if (session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif
    @if (session('error'))
        <x-ui.alert type="error" :message="session('error')" />
    @endif

    <div class="card bg-base-100 shadow">
        <div class="card-body p-0">
            <!-- Filtros -->
            <div class="p-4 border-b border-base-200">
                <form action="{{ route('categories.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-5">
                        <label class="input input-bordered flex items-center gap-2">
                            <i class="fas fa-search text-base-content/70"></i>
                            <input type="text" name="q" class="grow" placeholder="Buscar por nombre o descripción..." value="{{ $q ?? '' }}"/>
                        </label>
                    </div>
                    <div class="md:col-span-4">
                        <select name="type" class="select select-bordered w-full" onchange="this.form.submit()">
                            <option value="">Todos los tipos</option>
                            @foreach($typeFilters as $key => $label)
                                <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-filter mr-2"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de categorías -->
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-base-200 text-primary rounded-full w-10">
                                                <span><i class="fas fa-tag"></i></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $category->name }}</div>
                                            <div class="text-xs opacity-60">{{ $category->slug }}</div>
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
                                        $icons = [
                                            'libros' => 'fa-book',
                                            'utiles_escolares' => 'fa-pencil-ruler',
                                            'oficina' => 'fa-briefcase'
                                        ];
                                    @endphp
                                    <x-ui.badge :color="$color">
                                        <i class="fas {{ $icons[$category->type] ?? 'fa-tag' }} mr-1"></i>
                                        {{ $typeFilters[$category->type] ?? $category->type }}
                                    </x-ui.badge>
                                </td>
                                <td>
                                    <span class="badge">
                                        <i class="fas fa-boxes mr-1"></i>
                                        {{ $category->products_count }} {{ Str::plural('producto', $category->products_count) }}
                                    </span>
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                                    @else
                                        <span class="badge badge-ghost"><i class="fas fa-times-circle mr-1"></i> Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="join join-horizontal">
                                        <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-ghost join-item" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('categories.update')
                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-ghost join-item" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('categories.delete')
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="join-item inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?\n\nNota: Solo se pueden eliminar categorías sin productos asociados.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost text-error" title="Eliminar" {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    @if(request()->has('q') || request()->has('type'))
                                        <x-ui.empty-state title="Sin resultados"
                                                          description="No hay resultados para tu búsqueda. Intenta con otros términos o limpia los filtros."
                                                          :actionUrl="route('categories.index')"
                                                          actionLabel="Limpiar filtros"
                                                          actionColor="ghost"
                                                          :icon="'<i class=\'fas fa-inbox\'></i>'" />
                                    @else
                                        <x-ui.empty-state title="Aún no hay categorías"
                                                          description="Comienza creando una nueva categoría."
                                                          :actionUrl="auth()->user()->can('categories.create') ? route('categories.create') : null"
                                                          actionLabel="Crear Categoría"
                                                          actionColor="primary"
                                                          :icon="'<i class=\'fas fa-inbox\'></i>'" />
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-base-200 flex items-center justify-between text-sm">
                    <div class="opacity-70">
                        Mostrando <b>{{ $categories->firstItem() }}</b> a <b>{{ $categories->lastItem() }}</b> de <b>{{ $categories->total() }}</b> categorías
                    </div>
                    {{ $categories->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Bootstrap tooltips removed in favor of simpler titles; DaisyUI has no JS requirement here --}}

@endsection
