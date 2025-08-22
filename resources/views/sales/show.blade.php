@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Ventas', 'url' => route('sales.index')],
      ['label' => $sale->sale_number],
  ]" />

  <x-ui.page-header :title="'Venta ' . $sale->sale_number" :icon="'&lt;i class=\'fas fa-cash-register\'&gt;&lt;/i&gt;'">
    <x-slot name="actions">
      <a href="{{ route('sales.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
      @if($sale->status !== 'anulada')
      <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline">
        <i class="fas fa-edit mr-2"></i> Editar
      </a>
      @endif
      @if($sale->status !== 'anulada')
      <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('¿Anular esta venta y restaurar stock?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-error"><i class="fas fa-ban mr-2"></i> Anular</button>
      </form>
      @endif
    </x-slot>
  </x-ui.page-header>

  @if (session('status'))
    <x-ui.alert type="success">{{ session('status') }}</x-ui.alert>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
      <div class="card bg-base-100 shadow">
        <div class="card-body p-0">
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th class="text-right">Cant.</th>
                  <th class="text-right">P. Unit</th>
                  <th class="text-right">Desc. %</th>
                  <th class="text-right">Desc.</th>
                  <th class="text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($sale->details as $d)
                <tr>
                  <td>{{ $d->product->name }}</td>
                  <td class="text-right">{{ $d->quantity }}</td>
                  <td class="text-right">{{ number_format((float)$d->unit_price, 2) }}</td>
                  <td class="text-right">{{ number_format((float)$d->discount_percentage, 2) }}%</td>
                  <td class="text-right">{{ number_format((float)$d->discount_amount, 2) }}</td>
                  <td class="text-right">{{ number_format((float)$d->subtotal, 2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="card bg-base-100 shadow">
        <div class="card-body">
          <h2 class="card-title">Resumen</h2>
          <div class="space-y-2">
            <div class="flex justify-between"><span>Fecha</span><span>{{ $sale->sale_date->format('d/m/Y H:i') }}</span></div>
            <div class="flex justify-between"><span>Cliente</span><span>{{ $sale->customer->full_name }}</span></div>
            <div class="flex justify-between"><span>Método pago</span><span class="capitalize">{{ $sale->payment_method }}</span></div>
            <div class="flex justify-between"><span>Estado</span>
              <span class="badge {{ $sale->status === 'anulada' ? 'badge-error' : ($sale->status === 'pendiente' ? 'badge-warning' : 'badge-success') }}">{{ ucfirst($sale->status) }}</span>
            </div>
          </div>
          <div class="divider"></div>
          <div class="space-y-2">
            <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format((float)$sale->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span>Impuestos</span><span>{{ number_format((float)$sale->tax_amount, 2) }}</span></div>
            <div class="flex justify-between"><span>Descuentos</span><span>{{ number_format((float)$sale->discount_amount, 2) }}</span></div>
            <div class="flex justify-between font-semibold"><span>Total</span><span>{{ number_format((float)$sale->total, 2) }}</span></div>
          </div>
          @if($sale->notes)
          <div class="divider"></div>
          <div>
            <div class="text-sm opacity-70 mb-1">Notas</div>
            <div class="whitespace-pre-line">{{ $sale->notes }}</div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
