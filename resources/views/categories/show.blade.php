@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-ui.breadcrumbs :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Categorías', 'url' => route('categories.index')],
        ['label' => $category->name],
    ]" />

    <x-ui.page-header :title="$category->name" :icon="'<i class=\'fas fa-tag\'></i>'">
        <x-slot name="actions">
            <div class="join">
                <a href="{{ route('categories.index') }}" class="btn btn-ghost join-item">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
                @can('categories.update')
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary join-item">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                @endcan
            </div>
        </x-slot>
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow mb-6">
                <div class="card-body">
                    <h3 class="card-title text-base-content/80"><i class="fas fa-info-circle mr-2"></i>Información de la Categoría</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs uppercase opacity-70 mb-1">Nombre</div>
                                <div class="text-lg font-semibold">{{ $category->name }}</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase opacity-70 mb-1">Tipo</div>
                                <x-ui.badge color="primary">{{ ucfirst(str_replace('_',' ', $category->type)) }}</x-ui.badge>
                            </div>
                            <div>
                                <div class="text-xs uppercase opacity-70 mb-1">Estado</div>
                                <x-ui.badge color="{{ $category->status ? 'success' : 'ghost' }}">
                                    <i class="fas {{ $category->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $category->status ? 'Activo' : 'Inactivo' }}
                                </x-ui.badge>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs uppercase opacity-70 mb-1">Creada</div>
                                <div><i class="far fa-calendar-alt mr-2"></i>{{ $category->created_at->format('d/m/Y H:i') }}</div>
                                <div class="text-xs opacity-70">({{ $category->created_at->diffForHumans() }})</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase opacity-70 mb-1">Última Actualización</div>
                                <div><i class="far fa-clock mr-2"></i>{{ $category->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="text-xs opacity-70">({{ $category->updated_at->diffForHumans() }})</div>
                            </div>
                        </div>
                    </div>

                    @if($category->description)
                        <div class="divider"></div>
                        <div>
                            <div class="text-xs uppercase opacity-70 mb-2">Descripción</div>
                            <div class="p-3 rounded-box bg-base-200">{!! nl2br(e($category->description)) !!}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bg-base-100 shadow">
                <div class="card-body p-0">
                    <div class="px-6 pt-6 flex items-center justify-between">
                        <h3 class="card-title text-base-content/80"><i class="fas fa-boxes mr-2"></i>Productos en esta categoría</h3>
                        <span class="badge badge-primary">{{ $products->total() }}</span>
                    </div>

                    @if($products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-right">Precio</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                @if($product->image)
                                                    <div class="avatar">
                                                        <div class="w-10 rounded">
                                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" />
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="avatar placeholder">
                                                        <div class="bg-base-200 text-base-content/60 rounded w-10">
                                                            <i class="fas fa-box"></i>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-medium">{{ $product->name }}</div>
                                                    <div class="text-xs opacity-70">Código: {{ $product->code ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">{{ number_format($product->price, 2) }} Bs.</td>
                                        <td class="text-center">
                                            @if($product->stock <= $product->stock_alert)
                                                <span class="badge badge-error">{{ $product->stock }} unidades</span>
                                            @else
                                                <span class="badge badge-success">{{ $product->stock }} unidades</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-ghost btn-sm" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($products->hasPages())
                        <div class="px-6 pb-6 border-t border-base-200">
                            {{ $products->links() }}
                        </div>
                        @endif
                    @else
                        <div class="p-8">
                            <x-ui.empty-state :icon="'<i class=\'fas fa-box-open\'></i>'" title="No hay productos" description="No hay productos en esta categoría todavía.">
                                @can('products.create')
                                <x-slot name="action">
                                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i> Agregar Producto
                                    </a>
                                </x-slot>
                                @endcan
                            </x-ui.empty-state>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="card bg-base-100 shadow mb-6">
                <div class="card-body">
                    <h3 class="card-title text-base-content/80"><i class="fas fa-chart-pie mr-2"></i>Resumen</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="opacity-70">Total de Productos</span>
                            <span class="font-semibold">{{ $category->products_count }}</span>
                        </div>
                        @if($category->products_count > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Esta categoría tiene productos asociados.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title text-base-content/80"><i class="fas fa-bolt mr-2"></i>Acciones Rápidas</h3>
                    <div class="menu bg-base-100 rounded-box">
                        <ul>
                            @can('products.create')
                            <li>
                                <a href="{{ route('products.create', ['category_id' => $category->id]) }}">
                                    <i class="fas fa-plus-circle mr-2 text-success"></i>Agregar Producto
                                </a>
                            </li>
                            @endcan
                            @can('categories.products.export')
                            <li>
                                <a href="#">
                                    <i class="fas fa-file-export mr-2 text-info"></i>Exportar Productos
                                </a>
                            </li>
                            @endcan
                            @can('categories.update')
                            <li>
                                <a href="{{ route('categories.edit', $category) }}">
                                    <i class="fas fa-edit mr-2 text-warning"></i>Editar Categoría
                                </a>
                            </li>
                            @endcan
                            @can('categories.delete')
                            <li>
                                @if($category->products_count === 0)
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta categoría? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error">
                                            <i class="fas fa-trash-alt mr-2"></i>Eliminar Categoría
                                        </button>
                                    </form>
                                @else
                                    <span class="opacity-70"><i class="fas fa-info-circle mr-2"></i>No se puede eliminar porque tiene productos asociados.</span>
                                @endif
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
