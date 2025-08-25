@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Clientes'],
  ]" />

  <x-ui.page-header title="Clientes" icon="<i class='fas fa-user-friends'></i>">
    <x-slot name="actions">
      <form method="GET" action="{{ route('customers.index') }}" class="join">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="input input-bordered join-item" />
        <button type="submit" class="btn btn-outline join-item">Buscar</button>
        <a href="{{ route('customers.index') }}" class="btn btn-ghost join-item">Limpiar</a>
      </form>
      <a href="{{ route('customers.create') }}" class="btn btn-primary ml-3">
        <i class="fas fa-plus mr-2"></i>Nuevo
      </a>
    </x-slot>
  </x-ui.page-header>

  @if (session('status'))
    <x-ui.alert type="success">{{ session('status') }}</x-ui.alert>
  @endif

  <div class="card bg-base-100 shadow">
    <div class="card-body p-0">
      @if($customers->count())
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($customers as $c)
            <tr>
              <td class="font-medium">
                <a href="{{ route('customers.show', $c) }}" class="link">{{ $c->full_name }}</a>
              </td>
              <td>{{ $c->document_type }} {{ $c->document_number }}</td>
              <td>{{ $c->phone ?: '-' }}</td>
              <td>{{ $c->email ?: '-' }}</td>
              <td class="capitalize">{{ $c->customer_type }}</td>
              <td>
                <span class="badge {{ $c->status ? 'badge-success' : 'badge-ghost' }}">
                  {{ $c->status ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="text-right">
                <div class="join justify-end">
                  <a href="{{ route('customers.show', $c) }}" class="btn btn-sm btn-ghost join-item"><i class="fas fa-eye"></i></a>
                  <a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-outline join-item"><i class="fas fa-edit"></i></a>
                  <form action="{{ route('customers.destroy', $c) }}" method="POST" class="inline join-item" onsubmit="return confirm('¿Eliminar cliente?');">
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
      <div class="p-4">{{ $customers->links() }}</div>
      @else
        <x-ui.empty-state title="Sin clientes">
          <x-slot:description>Comienza creando un nuevo cliente.</x-slot:description>
          <x-slot:actions>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Nuevo cliente</a>
          </x-slot:actions>
        </x-ui.empty-state>
      @endif
    </div>
  </div>
</div>
@endsection
