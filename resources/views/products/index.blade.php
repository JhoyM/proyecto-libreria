@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Productos'],
  ]" />

  <x-ui.page-header title="Productos" :icon="'<i class=\'fas fa-boxes\'></i>'">
    <x-slot name="actions">
      <div class="join">
        <a href="{{ route('categories.index') }}" class="btn btn-ghost join-item">
          <i class="fas fa-tags mr-2"></i> Categorías
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary join-item">
          <i class="fas fa-plus mr-2"></i> Nuevo producto
        </a>
      </div>
    </x-slot>
  </x-ui.page-header>

  @if(session('success'))
    <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
  @endif

  @if($lowStockCount > 0)
    <x-ui.alert type="warning">
      Tienes <strong>{{ $lowStockCount }}</strong> {{ $lowStockCount === 1 ? 'producto' : 'productos' }} con stock bajo o al límite.
      <a class="link ml-2" href="{{ route('products.index', ['low_stock' => 1]) }}">Ver productos con stock bajo</a>
    </x-ui.alert>
  @endif

  <form method="get" class="mb-4">
    <div class="form-control w-full md:w-1/2">
      <label class="input input-bordered flex items-center gap-2">
        <i class="fas fa-search opacity-70"></i>
        <input type="text" class="grow" name="q" value="{{ $q }}" placeholder="Buscar por nombre o código" />
      </label>
    </div>
    <div class="mt-2">
      <button class="btn btn-ghost" type="submit">Buscar</button>
    </div>
  </form>

  @if($products->count() === 0)
    <x-ui.empty-state :icon="'<i class=\'fas fa-box-open\'></i>'" title="Sin productos" description="No hay productos por mostrar.">
      <x-slot name="action">
        <a href="{{ route('products.create') }}" class="btn btn-primary">
          <i class="fas fa-plus mr-2"></i> Nuevo producto
        </a>
      </x-slot>
    </x-ui.empty-state>
  @else
    <div class="card bg-base-100 shadow">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Proveedor</th>
              <th>Precio Venta</th>
              <th>Stock</th>
              <th>Estado</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $product)
              <tr class="{{ $product->stock <= $product->min_stock ? 'bg-warning/10' : '' }}">
                <td>{{ $product->code }}</td>
                <td class="whitespace-nowrap">{{ $product->name }}</td>
                <td>{{ $product->category?->name }}</td>
                <td>{{ $product->supplier?->name }}</td>
                <td>S/ {{ number_format($product->sale_price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                  <span class="badge {{ $product->status ? 'badge-success' : 'badge-ghost' }}">
                    {{ $product->status ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="text-right">
                  <div class="join join-horizontal">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost btn-sm join-item">Editar</a>
                    <form action="{{ route('products.destroy', $product) }}" method="post" onsubmit="return confirm('¿Eliminar este producto?')" class="join-item">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-ghost btn-sm text-error">Eliminar</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($products->hasPages())
      <div class="px-4 py-3 border-t border-base-200">
        {{ $products->links() }}
      </div>
      @endif
    </div>
  @endif
</div>
@endsection
