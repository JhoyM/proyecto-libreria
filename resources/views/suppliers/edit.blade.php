@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Proveedores', 'url' => route('suppliers.index')],
      ['label' => 'Editar proveedor'],
  ]" />

  <x-ui.page-header title="Editar proveedor" :icon="'&lt;i class=\'fas fa-truck\'&gt;&lt;/i&gt;'">
    <x-slot name="actions">
      <a href="{{ route('suppliers.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
    </x-slot>
  </x-ui.page-header>

  @if($errors->any())
    <x-ui.alert type="error">
      <strong>Revise los errores:</strong>
      <ul class="list-disc ml-6 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </x-ui.alert>
  @endif

  <div class="card bg-base-100 shadow">
    <div class="card-body">
      <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @method('PUT')
        @include('suppliers._form', ['submitLabel' => 'Actualizar'])
      </form>
    </div>
  </div>
</div>
@endsection
