<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleStoreRequest;
use App\Http\Requests\SaleUpdateRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));

        $sales = Sale::query()
            ->with(['customer','user'])
            ->when($q, function ($query) use ($q) {
                $query->where('sale_number', 'like', "%{$q}%")
                      ->orWhereHas('customer', function ($cq) use ($q) {
                          $cq->where('first_name', 'like', "%{$q}%")
                             ->orWhere('last_name', 'like', "%{$q}%");
                      });
            })
            ->orderByDesc('sale_date')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', compact('sales', 'q'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('first_name')->orderBy('last_name')->get();
        $products = Product::where('status', true)->orderBy('name')->get(['id','name','sale_price','stock']);
        $paymentMethods = ['efectivo','tarjeta','transferencia','credito'];
        return view('sales.create', compact('customers','products','paymentMethods'));
    }

    public function store(SaleStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            // Generate sale number
            $prefix = now()->format('Ymd');
            $rand = strtoupper(Str::random(4));
            $saleNumber = 'S-'.$prefix.'-'.$rand;

            $items = $data['items'];

            // Compute totals
            $subtotal = 0;
            $totalDiscount = 0;
            foreach ($items as $item) {
                $qty = (int) $item['quantity'];
                $unit = (float) $item['unit_price'];
                $discountPct = (float) ($item['discount_percentage'] ?? 0);
                $lineDiscount = round($qty * $unit * ($discountPct/100), 2);
                $lineSubtotal = round(($qty * $unit) - $lineDiscount, 2);
                $subtotal += $lineSubtotal;
                $totalDiscount += $lineDiscount;
            }

            $tax = (float) ($data['tax_amount'] ?? 0);
            $total = round($subtotal + $tax, 2);

            $sale = Sale::create([
                'sale_number' => $saleNumber,
                'customer_id' => $data['customer_id'],
                'user_id' => auth()->id(),
                'sale_date' => now(),
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'discount_amount' => $totalDiscount,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'status' => 'completada',
                'notes' => $data['notes'] ?? null,
            ]);

            // Persist details and adjust stock
            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                if ($qty <= 0) {
                    abort(422, 'La cantidad debe ser mayor a cero.');
                }
                if ($product->stock < $qty) {
                    abort(422, 'Stock insuficiente para el producto: '.$product->name);
                }

                $unit = (float) $item['unit_price'];
                $discountPct = (float) ($item['discount_percentage'] ?? 0);
                $discountAmount = round($qty * $unit * ($discountPct/100), 2);
                $lineSubtotal = round(($qty * $unit) - $discountAmount, 2);

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'discount_percentage' => $discountPct,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $lineSubtotal,
                ]);

                // adjust stock
                $product->decrement('stock', $qty);
            }

            return redirect()->route('sales.show', $sale)
                ->with('status', 'Venta registrada correctamente.');
        });
    }

    public function show(Sale $sale): View
    {
        $sale->load(['customer','user','details.product']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale): View
    {
        $sale->load(['details.product']);
        $customers = Customer::orderBy('first_name')->orderBy('last_name')->get();
        $products = Product::where('status', true)->orderBy('name')->get(['id','name','sale_price','stock']);
        $paymentMethods = ['efectivo','tarjeta','transferencia','credito'];
        return view('sales.edit', compact('sale','customers','products','paymentMethods'));
    }

    public function update(SaleUpdateRequest $request, Sale $sale): RedirectResponse
    {
        $data = $request->validated();

        return DB::transaction(function () use ($sale, $data) {
            // restore previous stock and remove details
            $sale->load('details.product');
            foreach ($sale->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }
            $sale->details()->delete();

            // recompute totals from new items
            $items = $data['items'];
            $subtotal = 0; $totalDiscount = 0;
            foreach ($items as $item) {
                $qty = (int) $item['quantity'];
                $unit = (float) $item['unit_price'];
                $discountPct = (float) ($item['discount_percentage'] ?? 0);
                $lineDiscount = round($qty * $unit * ($discountPct/100), 2);
                $lineSubtotal = round(($qty * $unit) - $lineDiscount, 2);
                $subtotal += $lineSubtotal; $totalDiscount += $lineDiscount;
            }
            $tax = (float) ($data['tax_amount'] ?? 0);
            $total = round($subtotal + $tax, 2);

            $sale->update([
                'customer_id' => $data['customer_id'],
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'discount_amount' => $totalDiscount,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            // add new details and decrement stock
            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                if ($product->stock < $qty) {
                    abort(422, 'Stock insuficiente para el producto: '.$product->name);
                }
                $unit = (float) $item['unit_price'];
                $discountPct = (float) ($item['discount_percentage'] ?? 0);
                $discountAmount = round($qty * $unit * ($discountPct/100), 2);
                $lineSubtotal = round(($qty * $unit) - $discountAmount, 2);

                $sale->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'discount_percentage' => $discountPct,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $lineSubtotal,
                ]);
                $product->decrement('stock', $qty);
            }

            return redirect()->route('sales.show', $sale)->with('status', 'Venta actualizada correctamente.');
        });
    }

    public function report(Request $request): View
    {
        $from = $request->date('from');
        $to = $request->date('to');
        $customerId = $request->integer('customer_id');
        $method = $request->get('payment_method');
        $status = $request->get('status');

        $query = Sale::query()->with('customer')
            ->when($from, fn($q) => $q->whereDate('sale_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('sale_date', '<=', $to))
            ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('sale_date');

        $sales = $query->paginate(15)->withQueryString();
        $totals = [
            'subtotal' => (clone $query)->sum('subtotal'),
            'tax' => (clone $query)->sum('tax_amount'),
            'discount' => (clone $query)->sum('discount_amount'),
            'total' => (clone $query)->sum('total'),
        ];

        $customers = Customer::orderBy('first_name')->orderBy('last_name')->get();
        $paymentMethods = ['efectivo','tarjeta','transferencia','credito'];
        $statuses = ['completada','anulada','pendiente'];

        return view('sales.report', compact('sales','totals','customers','paymentMethods','statuses','from','to','customerId','method','status'));
    }
    public function destroy(Sale $sale): RedirectResponse
    {
        if ($sale->status === 'anulada') {
            return redirect()->route('sales.show', $sale)->with('status', 'La venta ya estaba anulada.');
        }

        DB::transaction(function () use ($sale) {
            $sale->load('details.product');
            foreach ($sale->details as $detail) {
                // restore stock
                $detail->product->increment('stock', $detail->quantity);
            }
            $sale->update(['status' => 'anulada']);
        });

        return redirect()->route('sales.show', $sale)->with('status', 'Venta anulada y stock restaurado.');
    }
}
