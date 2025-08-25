@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Clientes', 'url' => route('customers.index')],
      ['label' => 'Nuevo cliente'],
  ]" />

  <x-ui.page-header title="Nuevo cliente" icon="<i class='fas fa-user-friends'></i>">
    <x-slot name="actions">
      <a href="{{ route('customers.index') }}" class="btn btn-ghost">
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
      <form action="{{ route('customers.store') }}" method="POST">
        @include('customers._form', ['submitLabel' => 'Crear'])
      </form>
    </div>
  </div>
</div>
@endsection
