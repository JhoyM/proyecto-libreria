@extends('layouts.app')

@section('title', 'Reportes de Ventas')

@section('content')
  <x-ui.page-header title="Reportes de Ventas">
    <x-slot name="actions">
      <a href="{{ route('dashboard') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>
      <a href="{{ route('sales.report', request()->query()) }}" class="btn btn-outline ml-2">
        <i class="fas fa-file-alt mr-2"></i> Reporte
      </a>
      <form method="GET" action="{{ route('reports.sales') }}" class="inline-flex items-end ml-2">
        <div class="mr-2">
          <label class="label"><span class="label-text">Agrupar</span></label>
          <select name="group" class="select select-bordered">
            <option value="month" {{ ($group ?? 'month')==='month' ? 'selected' : '' }}>Mensual</option>
            <option value="week" {{ ($group ?? '')==='week' ? 'selected' : '' }}>Semanal</option>
            <option value="day" {{ ($group ?? '')==='day' ? 'selected' : '' }}>Diario</option>
          </select>
        </div>
        <div class="mr-2">
          <label class="label"><span class="label-text">Desde</span></label>
          <input type="date" name="from" value="{{ $from?->format('Y-m-d') }}" class="input input-bordered" />
        </div>
        <div class="mr-2">
          <label class="label"><span class="label-text">Hasta</span></label>
          <input type="date" name="to" value="{{ $to?->format('Y-m-d') }}" class="input input-bordered" />
        </div>
        <button class="btn btn-primary">Aplicar</button>
      </form>
    </x-slot>
  </x-ui.page-header>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <h2 class="card-title">Ventas por {{ $group==='week' ? 'Semana' : ($group==='day' ? 'Día' : 'Mes') }}</h2>
        <canvas id="chartByMonth" height="120"></canvas>
      </div>
    </div>

    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <h2 class="card-title">Ventas por Categoría</h2>
        <canvas id="chartByCategory" height="120"></canvas>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const currencyFormatter = new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB', minimumFractionDigits: 2 });
    const params = new URLSearchParams({
      group: @json($group ?? 'month'),
      ...( @json($from?->format('Y-m-d')) ? {from: @json($from?->format('Y-m-d'))} : {} ),
      ...( @json($to?->format('Y-m-d')) ? {to: @json($to?->format('Y-m-d'))} : {} ),
    });

    async function fetchJson(url){
      const res = await fetch(url + '?' + params.toString(), { headers: { 'Accept': 'application/json' }});
      if(!res.ok) throw new Error('Error al cargar ' + url);
      return res.json();
    }

    (async () => {
      try {
        const [byMonth, byCategory] = await Promise.all([
          fetchJson({{ json_encode(route('reports.sales.by_month')) }}),
          fetchJson({{ json_encode(route('reports.sales.by_category')) }}),
        ]);

        const ctx1 = document.getElementById('chartByMonth').getContext('2d');
        new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: byMonth.labels,
            datasets: [{
              label: 'Total Ventas (Bs)',
              data: byMonth.data,
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
                ticks: {
                  callback: (value) => currencyFormatter.format(value)
                }
              }
            },
            plugins: { 
              legend: { display: false },
              tooltip: {
                callbacks: {
                  label: (ctx) => ` ${currencyFormatter.format(ctx.parsed.y ?? 0)}`
                }
              }
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
              tooltip: {
                callbacks: {
                  label: (ctx) => `${ctx.label}: ${currencyFormatter.format(ctx.parsed ?? 0)}`
                }
              }
            }
          }
        });
      } catch (e) {
        console.error(e);
        alert('No se pudieron cargar los datos de reportes.');
      }
    })();
  </script>
@endsection
