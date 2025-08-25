@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Ventas', 'url' => route('sales.index')],
      ['label' => 'Reporte'],
  ]" />

  <x-ui.page-header title="Reporte de ventas" icon="<i class='fas fa-chart-line'></i>">
    <x-slot name="actions">
      <a href="{{ route('sales.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
      <a
        href="{{ route('sales.report', array_filter(array_merge(request()->query(), ['export' => 'csv']))) }}"
        class="btn btn-outline ml-2"
      >
        <i class="fas fa-file-csv mr-2"></i> Exportar CSV
      </a>
      <a
        href="{{ route('sales.report', array_filter(array_merge(request()->query(), ['export' => 'pdf']))) }}"
        class="btn btn-outline ml-2"
      >
        <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
      </a>
      @if(request('view')==='charts')
        <a
          href="{{ route('sales.report', array_filter(array_merge(request()->query(), ['view' => null]))) }}"
          class="btn btn-primary ml-2"
        >
          <i class="fas fa-table mr-2"></i> Ver tabla
        </a>
      @else
        @role('Administrador')
        <a
          href="{{ route('sales.report', array_filter(array_merge(request()->query(), ['view' => 'charts']))) }}"
          class="btn btn-primary ml-2"
        >
          <i class="fas fa-chart-line mr-2"></i> Ver gráficos
        </a>
        @endrole
      @endif
    </x-slot>
  </x-ui.page-header>

  <div class="card bg-base-100 shadow mb-6">
    <div class="card-body">
      <form method="GET" action="{{ route('sales.report') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
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
        <div>
          <label class="label"><span class="label-text">Agrupar</span></label>
          <select name="group" class="select select-bordered w-full">
            @php $group = request('group','month'); @endphp
            <option value="month" {{ $group==='month' ? 'selected' : '' }}>Mensual</option>
            <option value="week" {{ $group==='week' ? 'selected' : '' }}>Semanal</option>
            <option value="day" {{ $group==='day' ? 'selected' : '' }}>Diario</option>
          </select>
        </div>
        <div class="md:col-span-6 flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-2"></i>Filtrar</button>
          <a href="{{ route('sales.report') }}" class="btn btn-ghost">Limpiar</a>
        </div>
      </form>
    </div>
  </div>

  @if(request('view')!=='charts')
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
  @endif
  @role('Administrador')
  @if(request('view')==='charts')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
      <div class="card bg-base-100 shadow">
        <div class="card-body">
          <h2 class="card-title">Ventas por {{ (request('group','month')==='week') ? 'Semana' : (request('group','month')==='day' ? 'Día' : 'Mes') }}</h2>
          <canvas id="chartByPeriod" height="120"></canvas>
        </div>
      </div>
      <div class="card bg-base-100 shadow">
        <div class="card-body">
          <h2 class="card-title">Ventas por Categoría</h2>
          <canvas id="chartByCategory" height="120"></canvas>
        </div>
      </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      const currencyFormatter = new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB', minimumFractionDigits: 2 });
      const params = new URLSearchParams({
        group: @json(request('group','month')),
        ...( @json($from?->format('Y-m-d')) ? {from: @json($from?->format('Y-m-d'))} : {} ),
        ...( @json($to?->format('Y-m-d')) ? {to: @json($to?->format('Y-m-d'))} : {} ),
        ...( @json($customerId) ? {customer_id: @json($customerId)} : {} ),
        ...( @json($method) ? {payment_method: @json($method)} : {} ),
        ...( @json($status) ? {status: @json($status)} : {} ),
      });

      async function fetchJson(url){
        const res = await fetch(url + '?' + params.toString(), { headers: { 'Accept': 'application/json' }});
        if(!res.ok) throw new Error('Error al cargar ' + url);
        return res.json();
      }

      (async () => {
        try {
          const [byPeriod, byCategory] = await Promise.all([
            fetchJson({{ json_encode(route('sales.report.by_period')) }}),
            fetchJson({{ json_encode(route('sales.report.by_category')) }}),
          ]);

          const ctx1 = document.getElementById('chartByPeriod').getContext('2d');
          new Chart(ctx1, {
            type: 'bar',
            data: {
              labels: byPeriod.labels,
              datasets: [{
                label: 'Total Ventas (Bs)',
                data: byPeriod.data,
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              scales: { 
                y: { 
                  beginAtZero: true,
                  ticks: { callback: (v) => currencyFormatter.format(v) }
                }
              },
              plugins: { 
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => ` ${currencyFormatter.format(ctx.parsed.y ?? 0)}` } }
              }
            }
          });

          const colors = ['#60a5fa','#f59e0b','#ef4444','#10b981','#a78bfa','#f472b6','#34d399','#22d3ee'];
          const ctx2 = document.getElementById('chartByCategory').getContext('2d');
          new Chart(ctx2, {
            type: 'doughnut',
            data: {
              labels: byCategory.labels,
              datasets: [{
                label: 'Ventas por Categoría',
                data: byCategory.data,
                backgroundColor: byCategory.labels.map((_, i) => colors[i % colors.length]),
              }]
            },
            options: {
              responsive: true,
              plugins: { 
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${currencyFormatter.format(ctx.parsed ?? 0)}` } }
              }
            }
          });
        } catch (e) {
          console.error(e);
        }
      })();
    </script>
    @endpush
  @endif
  @endrole
</div>
@endsection
