@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Ventas', 'url' => route('sales.index')],
      ['label' => 'Reporte'],
  ]" />

  <x-ui.page-header title="Reporte de ventas" :icon="'&lt;i class=\'fas fa-chart-line\'&gt;&lt;/i&gt;'">
    <x-slot name="actions">
      <a href="{{ route('sales.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
    </x-slot>
  </x-ui.page-header>

  <div class="card bg-base-100 shadow mb-6">
    <div class="card-body">
      <form method="GET" action="{{ route('sales.report') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
          <label class="label"><span class="label-text">Desde</span></label>
          <input type="date" name="from" value="{{ $from?->format('Y-m-d') }}" class="input input-bordered w-full" />
        </div>
        <div>
          <label class="label"><span class="label-text">Hasta</span></label>
          <input type="date" name="to" value="{{ $to?->format('Y-m-d') }}" class="input input-bordered w-full" />
        </div>
        <div>
          <label class="label"><span class="label-text">Cliente</span></label>
          <select name="customer_id" class="select select-bordered w-full">
            <option value="">Todos</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}" @selected($customerId===$c->id)>{{ $c->full_name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="label"><span class="label-text">Método</span></label>
          <select name="payment_method" class="select select-bordered w-full">
            <option value="">Todos</option>
            @foreach($paymentMethods as $m)
              <option value="{{ $m }}" @selected($method===$m)>{{ ucfirst($m) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="label"><span class="label-text">Estado</span></label>
          <select name="status" class="select select-bordered w-full">
            <option value="">Todos</option>
            @foreach($statuses as $s)
              <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div class="md:col-span-5 flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-2"></i>Filtrar</button>
          <a href="{{ route('sales.report') }}" class="btn btn-ghost">Limpiar</a>
        </div>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
      <div class="card bg-base-100 shadow">
        <div class="card-body p-0">
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
                </tr>
              </thead>
              <tbody>
                @forelse($sales as $s)
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
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center py-8 opacity-70">Sin resultados</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="p-4">{{ $sales->links() }}</div>
        </div>
      </div>
    </div>

    <div>
      <div class="card bg-base-100 shadow">
        <div class="card-body">
          <h2 class="card-title">Totales</h2>
          <div class="space-y-2">
            <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format((float)$totals['subtotal'], 2) }}</span></div>
            <div class="flex justify-between"><span>Impuestos</span><span>{{ number_format((float)$totals['tax'], 2) }}</span></div>
            <div class="flex justify-between"><span>Descuentos</span><span>{{ number_format((float)$totals['discount'], 2) }}</span></div>
            <div class="flex justify-between font-semibold"><span>Total</span><span>{{ number_format((float)$totals['total'], 2) }}</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
