@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Proveedores'],
  ]" />

  <x-ui.page-header title="Proveedores" icon="<i class='fas fa-truck'></i>">
    <x-slot name="actions">
      <form method="GET" action="{{ route('suppliers.index') }}" class="join">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="input input-bordered join-item" />
        <button type="submit" class="btn btn-outline join-item">Buscar</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-ghost join-item">Limpiar</a>
      </form>
      <a href="{{ route('suppliers.create') }}" class="btn btn-primary ml-3">
        <i class="fas fa-plus mr-2"></i>Nuevo
      </a>
    </x-slot>
  </x-ui.page-header>

  @if (session('status'))
    <x-ui.alert type="success">{{ session('status') }}</x-ui.alert>
  @endif

  <div class="card bg-base-100 shadow">
    <div class="card-body p-0">
      @if($suppliers->count())
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Contacto</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Estado</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($suppliers as $s)
            <tr>
              <td class="font-medium">
                <a href="{{ route('suppliers.show', $s) }}" class="link">{{ $s->name }}</a>
              </td>
              <td>{{ $s->contact_person ?: '-' }}</td>
              <td>{{ $s->phone ?: '-' }}</td>
              <td>{{ $s->email ?: '-' }}</td>
              <td>
                <span class="badge {{ $s->status ? 'badge-success' : 'badge-ghost' }}">
                  {{ $s->status ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="text-right">
                <div class="join justify-end">
                  <a href="{{ route('suppliers.show', $s) }}" class="btn btn-sm btn-ghost join-item"><i class="fas fa-eye"></i></a>
                  <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-sm btn-outline join-item"><i class="fas fa-edit"></i></a>
                  <form action="{{ route('suppliers.destroy', $s) }}" method="POST" class="inline join-item" onsubmit="return confirm('¿Eliminar proveedor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-error"><i class="fas fa-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="p-4">{{ $suppliers->links() }}</div>
      @else
        <x-ui.empty-state title="Sin proveedores">
          <x-slot:description>Comienza creando un nuevo proveedor.</x-slot:description>
          <x-slot:actions>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Nuevo proveedor</a>
          </x-slot:actions>
        </x-ui.empty-state>
      @endif
    </div>
  </div>
</div>
@endsection
