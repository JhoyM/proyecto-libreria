<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:suppliers.view')->only(['index', 'show']);
        $this->middleware('permission:suppliers.create')->only(['create', 'store']);
        $this->middleware('permission:suppliers.update')->only(['edit', 'update']);
        $this->middleware('permission:suppliers.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Supplier::query();

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $supplier = new Supplier([ 'status' => true ]);
        return view('suppliers.create', compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $supplier = Supplier::create($data);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Proveedor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier): View
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $supplier->update($data);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Proveedor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('status', 'Proveedor eliminado correctamente.');
    }
}
