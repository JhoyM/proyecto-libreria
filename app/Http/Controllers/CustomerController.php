<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers.view')->only(['index', 'show']);
        $this->middleware('permission:customers.create')->only(['create', 'store']);
        $this->middleware('permission:customers.update')->only(['edit', 'update']);
        $this->middleware('permission:customers.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('first_name')->orderBy('last_name')->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customer = new Customer(['status' => true, 'discount_percentage' => 0, 'document_type' => 'CI', 'customer_type' => 'regular']);
        return view('customers.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $customer = Customer::create($data);

        return redirect()->route('customers.show', $customer)
            ->with('status', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $customer->update($data);

        return redirect()->route('customers.show', $customer)
            ->with('status', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('status', 'Cliente eliminado correctamente.');
    }
}
