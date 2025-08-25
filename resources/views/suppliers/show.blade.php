@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Proveedores', 'url' => route('suppliers.index')],
      ['label' => $supplier->name],
  ]" />

  <x-ui.page-header :title="$supplier->name" icon="<i class='fas fa-truck'></i>">
    <x-slot name="actions">
      <a href="{{ route('suppliers.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
      <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary">
        <i class="fas fa-edit mr-2"></i> Editar
      </a>
    </x-slot>
  </x-ui.page-header>

  @if (session('status'))
    <x-ui.alert type="success">{{ session('status') }}</x-ui.alert>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <h2 class="card-title">Información</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="text-sm opacity-70">Persona de contacto</div>
            <div class="font-medium">{{ $supplier->contact_person ?: '-' }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">Teléfono</div>
            <div class="font-medium">{{ $supplier->phone ?: '-' }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">Email</div>
            <div class="font-medium">{{ $supplier->email ?: '-' }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">RUC/NIT</div>
            <div class="font-medium">{{ $supplier->tax_id ?: '-' }}</div>
          </div>
          <div class="md:col-span-2">
            <div class="text-sm opacity-70">Dirección</div>
            <div class="font-medium">{{ $supplier->address ?: '-' }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <h2 class="card-title">Estado</h2>
        <span class="badge {{ $supplier->status ? 'badge-success' : 'badge-ghost' }}">
          {{ $supplier->status ? 'Activo' : 'Inactivo' }}
        </span>
      </div>
    </div>
  </div>
</div>
@endsection
