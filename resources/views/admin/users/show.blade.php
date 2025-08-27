@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <x-ui.breadcrumbs :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Usuarios', 'url' => route('admin.users.index')],
        ['label' => $user->full_name],
    ]" />

    <x-ui.page-header :title="'Detalles del Usuario: ' . $user->full_name" icon="<i class='fas fa-user'></i>" />

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 sm:px-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-user text-2xl text-gray-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->status ? 'Activo' : 'Inactivo' }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Documento</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->document_number ?? 'No especificado' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'No especificado' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Roles</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-gray-500">Sin roles asignados</span>
                            @endforelse
                        </div>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Permisos</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            @php
                                $permissions = $user->getAllPermissions();
                            @endphp
                            @forelse($permissions as $permission)
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-gray-500">Sin permisos directos</span>
                            @endforelse
                        </div>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-3 bg-gray-50 text-right">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i> Editar Usuario
            </a>
        </div>
    </div>
</div>
@endsection
