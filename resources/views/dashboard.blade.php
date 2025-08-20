<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @can('products.index')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-boxes text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Productos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::count() }}</p>
                            </div>
                        </div>
                        @can('products.index')
                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                Ver todos los productos <span>&rarr;</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                @endcan

                @can('categories.index')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-tags text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Categorías</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Category::count() }}</p>
                            </div>
                        </div>
                        @can('categories.index')
                        <div class="mt-4">
                            <a href="{{ route('categories.index') }}" class="text-sm font-medium text-green-600 hover:text-green-500">
                                Ver todas las categorías <span>&rarr;</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                @endcan

                @can('products.index')
                @php
                    $lowStockCount = \App\Models\Product::where('stock', '<=', 5)->count();
                @endphp
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full {{ $lowStockCount > 0 ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600' }}">
                                <i class="fas fa-exclamation-triangle text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Productos con Stock Bajo</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $lowStockCount }}</p>
                            </div>
                        </div>
                        @if($lowStockCount > 0 && auth()->user()->can('products.index'))
                        <div class="mt-4">
                            <a href="{{ route('products.index', ['low_stock' => 1]) }}" class="text-sm font-medium {{ $lowStockCount > 0 ? 'text-red-600 hover:text-red-500' : 'text-yellow-600 hover:text-yellow-500' }}">
                                Ver productos con stock bajo <span>&rarr;</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endcan

                <!-- Add more stats cards here -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas fa-user-shield text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Rol de Usuario</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->getRoleNames()->first() ?? 'Sin rol' }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Ver perfil <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @can('products.create')
                        <a href="{{ route('products.create') }}" class="group flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Agregar Producto</p>
                                <p class="text-sm text-gray-500">Registra un nuevo producto en el inventario</p>
                            </div>
                        </a>
                        @endcan

                        @can('categories.create')
                        <a href="{{ route('categories.create') }}" class="group flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Nueva Categoría</p>
                                <p class="text-sm text-gray-500">Crea una nueva categoría de productos</p>
                            </div>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actividad Reciente</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Bienvenido de nuevo, {{ auth()->user()->name }}!</p>
                                <p class="text-sm text-gray-500">Has iniciado sesión correctamente.</p>
                                <p class="text-xs text-gray-400 mt-1">Hoy a las {{ now()->format('h:i A') }}</p>
                            </div>
                        </div>
                        <!-- Add more activity items here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
