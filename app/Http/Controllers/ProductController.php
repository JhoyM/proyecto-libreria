<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $lowStockCount;

    public function __construct()
    {
        // Protecciones por permiso (Spatie)
        $this->middleware(['permission:products.view'])->only(['index', 'show']);
        $this->middleware(['permission:products.create'])->only(['create', 'store']);
        $this->middleware(['permission:products.update'])->only(['edit', 'update']);
        $this->middleware(['permission:products.delete'])->only(['destroy']);
        
        // Compartir la cantidad de productos con stock bajo en todas las vistas
        $this->middleware(function ($request, $next) {
            $this->lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')
                ->where('stock', '>', 0)
                ->count();
            
            view()->share('lowStockCount', $this->lowStockCount);
            
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $q = $request->get('q');
        $lowStockFilter = $request->has('low_stock');
        
        $products = Product::with(['category', 'supplier'])
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'ilike', "%$q%")
                      ->orWhere('code', 'ilike', "%$q%");
            })
            ->when($lowStockFilter, function ($query) {
                $query->whereColumn('stock', '<=', 'min_stock')
                      ->where('stock', '>', 0);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'q' => $q,
            'lowStockFilter' => $lowStockFilter
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $suppliers = Supplier::orderBy('name')->pluck('name', 'id');
        $product = new Product();
        return view('products.create', compact('categories', 'suppliers', 'product'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);
        return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $suppliers = Supplier::orderBy('name')->pluck('name', 'id');
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        return view('products.show', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado.');
    }
}
