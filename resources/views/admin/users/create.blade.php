@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <x-ui.breadcrumbs :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Usuarios', 'url' => route('admin.users.index')],
        ['label' => 'Nuevo Usuario'],
    ]" />

    <x-ui.page-header title="Nuevo Usuario" icon="<i class='fas fa-user-plus'></i>" />

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="px-6 py-5 bg-white space-y-6 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <x-input-label for="first_name" value="Nombres" />
                        <x-text-input
                            id="first_name"
                            name="first_name"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('first_name')"
                            required
                        />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    <!-- Last Name -->
                    <div>
                        <x-input-label for="last_name" value="Apellidos" />
                        <x-text-input
                            id="last_name"
                            name="last_name"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('last_name')"
                            required
                        />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" value="Correo Electrónico" />
                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-1 block w-full"
                            :value="old('email')"
                            required
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Document Number -->
                    <div>
                        <x-input-label for="document_number" value="Número de Documento" />
                        <x-text-input
                            id="document_number"
                            name="document_number"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('document_number')"
                        />
                        <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" value="Teléfono" />
                        <x-text-input
                            id="phone"
                            name="phone"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('phone')"
                        />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <x-input-label for="status" value="Estado" />
                        <div class="mt-2">
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <span class="label-text">Usuario activo</span>
                                    <input
                                        type="checkbox"
                                        name="status"
                                        id="status"
                                        class="toggle toggle-primary"
                                        value="1"
                                        {{ old('status', true) ? 'checked' : '' }}
                                    >
                                </label>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="md:col-span-2">
                        <x-input-label for="password" value="Contraseña" />
                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="md:col-span-2">
                        <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                        <x-text-input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            required
                        />
                    </div>

                    <!-- Roles -->
                    <div class="md:col-span-2">
                        <x-input-label value="Roles" />
                        <div class="mt-2 space-y-2">
                            @forelse($roles as $role)
                                <div class="form-control">
                                    <label class="label cursor-pointer justify-start gap-2">
                                        <input
                                            id="role-{{ $role->id }}"
                                            name="roles[]"
                                            type="checkbox"
                                            value="{{ $role->id }}"
                                            class="checkbox checkbox-primary"
                                            {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                        >
                                        <span class="label-text">
                                            {{ $role->name }}
                                            @if($role->name === 'Administrador')
                                                <span class="text-xs text-gray-500">(Acceso completo al sistema)</span>
                                            @elseif($role->name === 'Vendedor')
                                                <span class="text-xs text-gray-500">(Gestión de ventas y clientes)</span>
                                            @elseif($role->name === 'Almacenero')
                                                <span class="text-xs text-gray-500">(Gestión de inventario y productos)</span>
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            @empty
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No hay roles disponibles. Por favor, ejecuta el seeder de roles y permisos.
                                </div>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 text-right">
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost mr-2">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
