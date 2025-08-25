<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1 { font-size: 18px; margin: 0 0 10px; }
    .muted { color: #666; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; }
    th { background: #f2f2f2; text-align: left; }
    .right { text-align: right; }
    .totals { margin-top: 12px; width: 50%; float: right; }
  </style>
</head>
<body>
  <h1>Reporte de ventas</h1>
  <div class="muted">
    @if($from) Desde: {{ $from->format('Y-m-d') }} @endif
    @if($to) &nbsp; Hasta: {{ $to->format('Y-m-d') }} @endif
    @if($customerId) &nbsp; Cliente ID: {{ $customerId }} @endif
    @if($method) &nbsp; Método: {{ ucfirst($method) }} @endif
    @if($status) &nbsp; Estado: {{ ucfirst($status) }} @endif
  </div>

  <table>
    <thead>
      <tr>
        <th>N°</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Método</th>
        <th>Estado</th>
        <th class="right">Subtotal</th>
        <th class="right">Impuestos</th>
        <th class="right">Descuentos</th>
        <th class="right">Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse($sales as $s)
        <tr>
          <td>{{ $s->sale_number }}</td>
          <td>{{ optional($s->sale_date)->format('Y-m-d H:i') }}</td>
          <td>{{ optional($s->customer)->full_name }}</td>
          <td>{{ $s->payment_method }}</td>
          <td>{{ ucfirst($s->status) }}</td>
          <td class="right">{{ number_format((float)$s->subtotal, 2) }}</td>
          <td class="right">{{ number_format((float)$s->tax_amount, 2) }}</td>
          <td class="right">{{ number_format((float)$s->discount_amount, 2) }}</td>
          <td class="right">{{ number_format((float)$s->total, 2) }}</td>
        </tr>
      @empty
        <tr><td colspan="9" class="right">Sin resultados</td></tr>
      @endforelse
    </tbody>
  </table>

  <table class="totals">
    <tr><th>Total Subtotal</th><td class="right">{{ number_format((float)$totals['subtotal'], 2) }}</td></tr>
    <tr><th>Total Impuestos</th><td class="right">{{ number_format((float)$totals['tax'], 2) }}</td></tr>
    <tr><th>Total Descuentos</th><td class="right">{{ number_format((float)$totals['discount'], 2) }}</td></tr>
    <tr><th>Total</th><td class="right"><strong>{{ number_format((float)$totals['total'], 2) }}</strong></td></tr>
  </table>
</body>
</html>
