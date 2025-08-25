<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View as ViewContract;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function sales(Request $request): ViewContract
    {
        $from = $request->date('from');
        $to = $request->date('to');
        $group = $request->get('group', 'month'); // month|week|day

        return view('reports.sales', compact('from', 'to', 'group'));
    }

    public function salesByMonth(Request $request): JsonResponse
    {
        $from = $request->date('from');
        $to = $request->date('to');
        $group = $request->get('group', 'month'); // month|week|day

        $query = Sale::query();
        if ($from) { $query->whereDate('sale_date', '>=', $from); }
        if ($to) { $query->whereDate('sale_date', '<=', $to); }

        // Traer las ventas en el rango (o últimos 12 meses si no hay filtros)
        if (!$from && !$to) {
            $start = now()->startOfMonth()->subMonths(11);
            $query->whereDate('sale_date', '>=', $start);
        }

        $sales = $query->get(['sale_date','total']);

        // Agrupar en PHP para evitar dependencias específicas de BD
        $buckets = [];
        foreach ($sales as $s) {
            $date = $s->sale_date instanceof \DateTimeInterface ? $s->sale_date : Carbon::parse($s->sale_date);
            switch ($group) {
                case 'day':
                    $key = $date->format('Y-m-d'); break;
                case 'week':
                    $key = $date->format('o-\WW'); break; // ISO week
                case 'month':
                default:
                    $key = $date->format('Y-m'); break;
            }
            $buckets[$key] = ($buckets[$key] ?? 0) + (float)$s->total;
        }

        // Rellenar buckets si es mensual sin filtros
        if ($group === 'month' && !$from && !$to) {
            $cursor = now()->startOfMonth()->subMonths(11);
            for ($i = 0; $i < 12; $i++) {
                $key = $cursor->copy()->addMonths($i)->format('Y-m');
                if (!array_key_exists($key, $buckets)) {
                    $buckets[$key] = 0.0;
                }
            }
        }

        ksort($buckets);
        return response()->json([
            'labels' => array_keys($buckets),
            'data' => array_values($buckets),
        ]);
    }

    public function salesByCategory(Request $request): JsonResponse
    {
        $from = $request->date('from');
        $to = $request->date('to');

        // Sumatoria por categoría basada en SaleDetail->subtotal
        $details = SaleDetail::query()
            ->with(['sale','product.category'])
            ->whereHas('sale', function($q) use ($from, $to) {
                if ($from) { $q->whereDate('sale_date', '>=', $from); }
                if ($to) { $q->whereDate('sale_date', '<=', $to); }
            })
            ->get();

        $totals = [];
        foreach ($details as $d) {
            $cat = optional(optional($d->product)->category)->name ?? 'Sin categoría';
            $totals[$cat] = ($totals[$cat] ?? 0) + (float)$d->subtotal;
        }

        arsort($totals);
        return response()->json([
            'labels' => array_keys($totals),
            'data' => array_values($totals),
        ]);
    }
}
