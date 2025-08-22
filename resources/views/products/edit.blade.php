@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Productos', 'url' => route('products.index')],
      ['label' => 'Editar producto'],
  ]" />

  <x-ui.page-header title="Editar producto" :icon="'<i class=\'fas fa-box\'></i>'">
    <x-slot name="actions">
      <a href="{{ route('products.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
    </x-slot>
  </x-ui.page-header>

  @if($errors->any())
    <x-ui.alert type="error">
      <strong>Revise los errores:</strong>
      <ul class="list-disc list-inside mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </x-ui.alert>
  @endif

  <form method="post" action="{{ route('products.update', $product) }}" class="card bg-base-100 shadow p-4">
    @method('PUT')
    @include('products._form')
  </form>
</div>
@endsection
