@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-base-content">
    <!-- Page Header -->
    <div class="breadcrumbs text-sm mb-4">
        <ul>
            <li><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li>Panel de Control</li>
        </ul>
    </div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Panel de Control</h1>
    </div>

    <!-- Stats -->
    <div class="stats stats-vertical lg:stats-horizontal shadow w-full mb-6">
        @can('products.index')
        <div class="stat">
            <div class="stat-figure text-primary">📦</div>
            <div class="stat-title">Total Productos</div>
            <div class="stat-value text-primary">{{ \App\Models\Product::count() }}</div>
            <div class="stat-desc">Gestión de inventario</div>
        </div>
        @endcan
        @can('categories.index')
        <div class="stat">
            <div class="stat-figure text-secondary">🏷️</div>
            <div class="stat-title">Total Categorías</div>
            <div class="stat-value text-secondary">{{ \App\Models\Category::count() }}</div>
            <div class="stat-desc">Organización del catálogo</div>
        </div>
        @endcan
        <div class="stat">
            <div class="stat-figure text-warning">⚠️</div>
            <div class="stat-title">Stock Bajo</div>
            <div class="stat-value text-warning">{{ \App\Models\Product::whereColumn('stock','<=','min_stock')->where('stock','>',0)->count() }}</div>
            <div class="stat-desc">Productos por reponer</div>
        </div>
        <div class="stat">
            <div class="stat-figure text-accent">🛡️</div>
            <div class="stat-title">Rol</div>
            <div class="stat-value text-accent">{{ auth()->user()->roles->pluck('name')->first() ?? 'Usuario' }}</div>
            <div class="stat-desc">Permisos activos</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @can('products.create')
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Crear Producto</h2>
                <p>Agrega un nuevo producto al inventario.</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Nuevo</a>
                </div>
            </div>
        </div>
        @endcan
        @can('categories.create')
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Crear Categoría</h2>
                <p>Organiza los productos por categoría.</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('categories.create') }}" class="btn btn-secondary">Nueva</a>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <!-- Module Navigation Cards -->
    <div class="mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @can('products.index')
            <div class="card bg-base-100 shadow hover:shadow-lg transition">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="card-title"><i class="fas fa-boxes mr-2"></i>Productos</h3>
                            <p class="text-sm text-base-content/70">Gestiona el inventario</p>
                        </div>
                        <div class="badge badge-primary">{{ \App\Models\Product::count() }}</div>
                    </div>
                    <div class="card-actions justify-end mt-2">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Ver productos</a>
                        @can('products.create')
                        <a href="{{ route('products.create') }}" class="btn btn-ghost">Nuevo</a>
                        @endcan
                    </div>
                </div>
            </div>
            @endcan

            @can('categories.index')
            <div class="card bg-base-100 shadow hover:shadow-lg transition">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="card-title"><i class="fas fa-tags mr-2"></i>Categorías</h3>
                            <p class="text-sm text-base-content/70">Organiza el catálogo</p>
                        </div>
                        <div class="badge badge-secondary">{{ \App\Models\Category::count() }}</div>
                    </div>
                    <div class="card-actions justify-end mt-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Ver categorías</a>
                        @can('categories.create')
                        <a href="{{ route('categories.create') }}" class="btn btn-ghost">Nueva</a>
                        @endcan
                    </div>
                </div>
            </div>
            @endcan

            @if(Route::has('suppliers.index'))
            @can('suppliers.index')
            <div class="card bg-base-100 shadow hover:shadow-lg transition">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="card-title"><i class="fas fa-truck mr-2"></i>Proveedores</h3>
                            <p class="text-sm text-base-content/70">Gestiona proveedores</p>
                        </div>
                        <div class="badge">{{ \App\Models\Supplier::count() }}</div>
                    </div>
                    <div class="card-actions justify-end mt-2">
                        <a href="{{ route('suppliers.index') }}" class="btn">Ver proveedores</a>
                        @can('suppliers.create')
                        <a href="{{ route('suppliers.create') }}" class="btn btn-ghost">Nuevo</a>
                        @endcan
                    </div>
                </div>
            </div>
            @endcan
            @endif
        </div>
    </div>
</div>
@endsection
