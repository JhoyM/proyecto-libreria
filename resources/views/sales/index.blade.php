@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Ventas'],
  ]" />

  <x-ui.page-header title="Ventas" icon="<i class='fas fa-cash-register'></i>">
    <x-slot name="actions">
      <form method="GET" action="{{ route('sales.index') }}" class="join">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por N° o cliente..." class="input input-bordered join-item" />
        <button type="submit" class="btn btn-outline join-item">Buscar</button>
        <a href="{{ route('sales.index') }}" class="btn btn-ghost join-item">Limpiar</a>
      </form>
      <a href="{{ route('sales.report') }}" class="btn btn-outline ml-3">
        <i class="fas fa-chart-line mr-2"></i> Reporte
      </a>
      <a href="{{ route('sales.create') }}" class="btn btn-primary ml-3">
        <i class="fas fa-plus mr-2"></i>Nueva venta
      </a>
    </x-slot>
  </x-ui.page-header>

  @if (session('status'))
    <x-ui.alert type="success">{{ session('status') }}</x-ui.alert>
  @endif

  <div class="card bg-base-100 shadow">
    <div class="card-body p-0">
      @if($sales->count())
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>N°</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Método</th>
              <th>Total</th>
              <th>Estado</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sales as $s)
            <tr>
              <td class="font-medium"><a class="link" href="{{ route('sales.show', $s) }}">{{ $s->sale_number }}</a></td>
              <td>{{ $s->sale_date->format('d/m/Y H:i') }}</td>
              <td>{{ $s->customer->full_name }}</td>
              <td class="capitalize">{{ $s->payment_method }}</td>
              <td>{{ number_format((float)$s->total, 2) }}</td>
              <td>
                <span class="badge {{ $s->status === 'anulada' ? 'badge-error' : ($s->status === 'pendiente' ? 'badge-warning' : 'badge-success') }}">
                  {{ ucfirst($s->status) }}
                </span>
              </td>
              <td class="text-right">
                <div class="join justify-end">
                  <a href="{{ route('sales.show', $s) }}" class="btn btn-sm btn-ghost join-item"><i class="fas fa-eye"></i></a>
                  @if($s->status !== 'anulada')
                  <a href="{{ route('sales.edit', $s) }}" class="btn btn-sm btn-ghost join-item"><i class="fas fa-edit"></i></a>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="p-4">{{ $sales->links() }}</div>
      @else
        <x-ui.empty-state title="Sin ventas">
          <x-slot:description>Registra tu primera venta.</x-slot:description>
          <x-slot:actions>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">Nueva venta</a>
          </x-slot:actions>
        </x-ui.empty-state>
      @endif
    </div>
  </div>
</div>
@endsection
