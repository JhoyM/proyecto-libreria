<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gate/Policy can be added later
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'contact_person' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:500'],
            'tax_id' => ['nullable','string','max:100'],
            'status' => ['nullable','boolean'],
        ];
    }
}
