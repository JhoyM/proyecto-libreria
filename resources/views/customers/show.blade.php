@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Clientes', 'url' => route('customers.index')],
      ['label' => $customer->full_name],
  ]" />

  <x-ui.page-header :title="$customer->full_name" :icon="'&lt;i class=\'fas fa-user-friends\'&gt;&lt;/i&gt;'">
    <x-slot name="actions">
      <a href="{{ route('customers.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
      <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
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
            <div class="text-sm opacity-70">Documento</div>
            <div class="font-medium">{{ $customer->document_type }} {{ $customer->document_number }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">Teléfono</div>
            <div class="font-medium">{{ $customer->phone ?: '-' }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">Email</div>
            <div class="font-medium">{{ $customer->email ?: '-' }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">Fecha nacimiento</div>
            <div class="font-medium">{{ optional($customer->birth_date)->format('d/m/Y') ?: '-' }}</div>
          </div>
          <div class="md:col-span-2">
            <div class="text-sm opacity-70">Dirección</div>
            <div class="font-medium">{{ $customer->address ?: '-' }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <h2 class="card-title">Detalles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="text-sm opacity-70">Tipo</div>
            <div class="font-medium capitalize">{{ $customer->customer_type }}</div>
          </div>
          <div>
            <div class="text-sm opacity-70">% Descuento</div>
            <div class="font-medium">{{ number_format((float)$customer->discount_percentage, 2) }}%</div>
          </div>
        </div>
        <div class="mt-4">
          <div class="text-sm opacity-70">Estado</div>
          <span class="badge {{ $customer->status ? 'badge-success' : 'badge-ghost' }}">
            {{ $customer->status ? 'Activo' : 'Inactivo' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
