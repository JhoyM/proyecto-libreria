@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <x-ui.breadcrumbs :items="[
      ['label' => 'Inicio', 'url' => route('dashboard')],
      ['label' => 'Ventas', 'url' => route('sales.index')],
      ['label' => 'Editar ' . $sale->sale_number],
  ]" />

  <x-ui.page-header :title="'Editar venta ' . $sale->sale_number" :icon="'&lt;i class=\'fas fa-cash-register\'&gt;&lt;/i&gt;'">
    <x-slot name="actions">
      <a href="{{ route('sales.show', $sale) }}" class="btn btn-ghost">
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

  <form action="{{ route('sales.update', $sale) }}" method="POST" id="saleForm">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow mb-6">
          <div class="card-body">
            <h2 class="card-title">Datos del cliente</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="md:col-span-2">
                <label class="label"><span class="label-text">Cliente</span></label>
                <select name="customer_id" class="select select-bordered w-full" required>
                  <option value="">Seleccione...</option>
                  @foreach($customers as $c)
                    <option value="{{ $c->id }}" @selected($sale->customer_id === $c->id)>{{ $c->full_name }}</option>
                  @endforeach
                </select>
                @error('customer_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="label"><span class="label-text">Método de pago</span></label>
                <select name="payment_method" class="select select-bordered w-full" required>
                  @foreach($paymentMethods as $m)
                    <option value="{{ $m }}" @selected($sale->payment_method === $m)>{{ ucfirst($m) }}</option>
                  @endforeach
                </select>
                @error('payment_method')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="card bg-base-100 shadow">
          <div class="card-body">
            <h2 class="card-title">Productos</h2>

            <div class="overflow-x-auto">
              <table class="table" id="itemsTable">
                <thead>
                  <tr>
                    <th style="width: 40%">Producto</th>
                    <th style="width: 10%">Stock</th>
                    <th style="width: 10%">Cant.</th>
                    <th style="width: 15%">P. Unit</th>
                    <th style="width: 10%">Desc. %</th>
                    <th style="width: 15%" class="text-right">Subtotal</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="itemsBody">
                </tbody>
              </table>
            </div>

            <div class="mt-4">
              <button type="button" class="btn btn-outline" id="addItemBtn"><i class="fas fa-plus mr-2"></i>Agregar producto</button>
              @error('items')<p class="text-error text-sm mt-2">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="card bg-base-100 shadow">
          <div class="card-body">
            <h2 class="card-title">Totales</h2>

            <div class="space-y-3">
              <div class="flex justify-between">
                <span>Subtotal</span>
                <span id="subtotalText">0.00</span>
              </div>
              <div class="flex justify-between items-center">
                <span>Impuestos</span>
                <input type="number" step="0.01" min="0" name="tax_amount" id="taxAmount" class="input input-bordered input-sm w-32 text-right" value="{{ $sale->tax_amount }}">
              </div>
              <div class="flex justify-between font-semibold">
                <span>Total</span>
                <span id="totalText">0.00</span>
              </div>
            </div>

            <div class="mt-4">
              <label class="label"><span class="label-text">Notas</span></label>
              <textarea name="notes" rows="3" class="textarea textarea-bordered w-full">{{ $sale->notes }}</textarea>
            </div>

            <div class="mt-6">
              <button type="submit" class="btn btn-primary w-full">Guardar cambios</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

@push('scripts')
<script>
(function(){
  const products = @json($products);
  const existing = @json($sale->details->map(fn($d) => [
    'product_id' => $d->product_id,
    'quantity' => $d->quantity,
    'unit_price' => $d->unit_price,
    'discount_percentage' => $d->discount_percentage,
  ]));

  const itemsBody = document.getElementById('itemsBody');
  const addBtn = document.getElementById('addItemBtn');
  const subtotalText = document.getElementById('subtotalText');
  const totalText = document.getElementById('totalText');
  const taxInput = document.getElementById('taxAmount');

  function fmt(n){ return (Number(n)||0).toFixed(2); }

  function buildProductOptions(selected){
    const opts = ['<option value="">Seleccione...</option>'];
    products.forEach(p => {
      const sel = selected && Number(selected) === Number(p.id) ? ' selected' : '';
      opts.push(`<option data-price="${p.sale_price}" data-stock="${p.stock}" value="${p.id}"${sel}>${p.name}</option>`);
    });
    return opts.join('');
  }

  function addRow(prefill){
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <select name="items[][product_id]" class="select select-bordered w-full item-product">${buildProductOptions(prefill?.product_id)}</select>
      </td>
      <td><span class="item-stock">-</span></td>
      <td><input type="number" name="items[][quantity]" class="input input-bordered input-sm w-20 item-qty" min="1" value="${prefill?.quantity || 1}"></td>
      <td><input type="number" step="0.01" min="0" name="items[][unit_price]" class="input input-bordered input-sm w-28 text-right item-price" value="${prefill?.unit_price || 0}"></td>
      <td><input type="number" step="0.01" min="0" max="100" name="items[][discount_percentage]" class="input input-bordered input-sm w-20 text-right item-disc" value="${prefill?.discount_percentage || 0}"></td>
      <td class="text-right font-medium"><span class="item-subtotal">0.00</span></td>
      <td><button type="button" class="btn btn-sm btn-ghost text-error item-remove"><i class="fas fa-trash"></i></button></td>
    `;
    itemsBody.appendChild(tr);
    attachHandlers(tr);
    applyStockAndPrice(tr);
    recalcRow(tr);
    recalcTotals();
  }

  function applyStockAndPrice(tr){
    const sel = tr.querySelector('.item-product');
    const opt = sel.options[sel.selectedIndex];
    if(opt){
      tr.querySelector('.item-price').value = opt.getAttribute('data-price') || tr.querySelector('.item-price').value;
      tr.querySelector('.item-stock').textContent = opt.getAttribute('data-stock') ?? '-';
    }
  }

  function attachHandlers(tr){
    const sel = tr.querySelector('.item-product');
    const qty = tr.querySelector('.item-qty');
    const price = tr.querySelector('.item-price');
    const disc = tr.querySelector('.item-disc');
    const rm = tr.querySelector('.item-remove');

    sel.addEventListener('change', () => { applyStockAndPrice(tr); recalcRow(tr); recalcTotals(); });
    qty.addEventListener('input', () => { recalcRow(tr); recalcTotals(); });
    price.addEventListener('input', () => { recalcRow(tr); recalcTotals(); });
    disc.addEventListener('input', () => { recalcRow(tr); recalcTotals(); });
    rm.addEventListener('click', () => { tr.remove(); recalcTotals(); });
  }

  function recalcRow(tr){
    const qty = Number(tr.querySelector('.item-qty').value)||0;
    const price = Number(tr.querySelector('.item-price').value)||0;
    const disc = Number(tr.querySelector('.item-disc').value)||0;
    const subtotal = (qty * price) * (1 - (disc/100));
    tr.querySelector('.item-subtotal').textContent = fmt(subtotal);
  }

  function recalcTotals(){
    let subtotal = 0;
    itemsBody.querySelectorAll('.item-subtotal').forEach(el => subtotal += Number(el.textContent)||0);
    subtotalText.textContent = fmt(subtotal);
    const tax = Number(taxInput.value)||0;
    totalText.textContent = fmt(subtotal + tax);
  }

  addBtn.addEventListener('click', () => addRow());
  taxInput.addEventListener('input', recalcTotals);

  // preload existing rows
  if(Array.isArray(existing)){
    existing.forEach(addRow);
  }
  recalcTotals();
})();
</script>
@endpush
@endsection
