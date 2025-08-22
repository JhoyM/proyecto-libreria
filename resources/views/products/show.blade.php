@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Productos', 'url' => route('products.index')],
      ['label' => $product->name],
  ]" />

  <x-ui.page-header :title="$product->name" :icon="'<i class=\'fas fa-box\'></i>'">
    <x-slot name="actions">
      <div class="join">
        <a href="{{ route('products.index') }}" class="btn btn-ghost join-item">
          <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
        @can('products.update')
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary join-item">
          <i class="fas fa-edit mr-2"></i> Editar
        </a>
        @endcan
      </div>
    </x-slot>
  </x-ui.page-header>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
      <div class="card bg-base-100 shadow">
        <div class="card-body">
          <h2 class="card-title"><i class="fas fa-info-circle text-primary mr-2"></i>Información del Producto</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div>
              <div class="mb-2">
                <div class="text-xs uppercase text-base-content/70 mb-1">Nombre</div>
                <div class="text-lg font-medium">{{ $product->name }}</div>
              </div>
              <div class="mb-2">
                <div class="text-xs uppercase text-base-content/70 mb-1">Código</div>
                <div>{{ $product->code ?? 'N/A' }}</div>
              </div>
              <div class="mb-2">
                <div class="text-xs uppercase text-base-content/70 mb-1">Precio venta</div>
                <div>S/ {{ number_format($product->sale_price ?? $product->price ?? 0, 2) }}</div>
              </div>
            </div>
            <div>
              <div class="mb-2">
                <div class="text-xs uppercase text-base-content/70 mb-1">Categoría</div>
                @if($product->category)
                  <a href="{{ route('categories.show', $product->category) }}" class="link">
                    <i class="fas fa-tag mr-1"></i>{{ $product->category->name }}
                  </a>
                @else
                  <span class="text-base-content/60">Sin categoría</span>
                @endif
              </div>
              <div class="mb-2">
                <div class="text-xs uppercase text-base-content/70 mb-1">Proveedor</div>
                <div>{{ $product->supplier->name ?? 'N/A' }}</div>
              </div>
              <div class="mb-2">
                <div class="text-xs uppercase text-base-content/70 mb-1">Stock</div>
                <div>{{ $product->stock }} unidades</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="card bg-base-100 shadow">
        <div class="card-body">
          <h2 class="card-title"><i class="fas fa-image text-primary mr-2"></i>Imagen</h2>
          @php($imgPath = $product->image ?? $product->image_path ?? null)
          @if($imgPath)
            <figure class="mt-2">
              <img src="{{ Str::startsWith($imgPath, ['http://','https://']) ? $imgPath : asset('storage/'.$imgPath) }}" alt="{{ $product->name }}" class="rounded-md w-full object-cover">
            </figure>
          @else
            <div class="text-center text-base-content/60 py-10">
              <i class="fas fa-image fa-2x mb-2"></i>
              <div>Sin imagen</div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
