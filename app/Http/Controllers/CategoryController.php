<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $categoryTypes = [
        'libros' => 'Libros',
        'utiles_escolares' => 'Útiles Escolares',
        'oficina' => 'Oficina'
    ];

    public function __construct()
    {
        $this->middleware(['permission:categories.view'])->only(['index', 'show']);
        $this->middleware(['permission:categories.create'])->only(['create', 'store']);
        $this->middleware(['permission:categories.update'])->only(['edit', 'update']);
        $this->middleware(['permission:categories.delete'])->only(['destroy']);
        
        // Compartir tipos de categorías con todas las vistas
        view()->share('categoryTypes', $this->categoryTypes);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $type = $request->get('type');
        
        $categories = Category::withCount('products')
            ->when($q, function($query) use ($q) {
                $query->where('name', 'ilike', "%$q%")
                      ->orWhere('description', 'ilike', "%$q%");
            })
            ->when($type, function($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
            
        return view('categories.index', [
            'categories' => $categories,
            'q' => $q,
            'type' => $type,
            'typeFilters' => $this->categoryTypes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new Category();
        return view('categories.create', [
            'category' => $category,
            'categoryTypes' => $this->categoryTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:' . implode(',', array_keys($this->categoryTypes)),
            'status' => 'sometimes|accepted',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.unique' => 'Ya existe una categoría con este nombre',
            'type.required' => 'El tipo de categoría es obligatorio',
            'description.max' => 'La descripción no debe superar los 500 caracteres',
        ]);

        // Prepare data for creation
        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'status' => $request->has('status'),
            // Slug will be automatically generated in the model
        ];

        DB::beginTransaction();
        try {
            // Create the category
            $category = Category::create($data);
            
            DB::commit();
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría creada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Log::error('Error al crear categoría: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear la categoría. Por favor, intente nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $products = $category->products()
            ->with('supplier')
            ->orderBy('name')
            ->paginate(10);
            
        return view('categories.show', [
            'category' => $category,
            'products' => $products,
            'categoryTypes' => $this->categoryTypes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', [
            'category' => $category,
            'categoryTypes' => $this->categoryTypes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:' . implode(',', array_keys($this->categoryTypes)),
            'status' => 'sometimes|accepted',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.unique' => 'Ya existe otra categoría con este nombre',
            'type.required' => 'El tipo de categoría es obligatorio',
            'description.max' => 'La descripción no debe superar los 500 caracteres',
        ]);

        // Prepare data for update
        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'status' => $request->has('status'),
        ];

        DB::beginTransaction();
        try {
            // Update the category
            $category->update($data);
            
            DB::commit();
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría actualizada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Log::error('Error al actualizar categoría: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la categoría. Por favor, intente nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Verificar si hay productos asociados
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        DB::beginTransaction();
        try {
            $category->delete();
            DB::commit();
            return redirect()->route('categories.index')
                ->with('success', 'Categoría eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}
