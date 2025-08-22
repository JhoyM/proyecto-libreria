<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // use policies if needed
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required','exists:customers,id'],
            'payment_method' => ['required','in:efectivo,tarjeta,transferencia,credito'],
            'tax_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string'],

            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','exists:products,id'],
            'items.*.quantity' => ['required','integer','min:1'],
            'items.*.unit_price' => ['required','numeric','min:0'],
            'items.*.discount_percentage' => ['nullable','numeric','min:0','max:100'],
        ];
    }
}
