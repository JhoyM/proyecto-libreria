<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Apply policies/gates later if needed
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'document_type' => ['required','in:CI,NIT,Pasaporte'],
            'document_number' => ['required','string','max:20', Rule::unique('customers')->ignore($this->customer)],
            'phone' => ['nullable','string','max:20'],
            'email' => ['nullable','email','max:100'],
            'address' => ['nullable','string'],
            'birth_date' => ['nullable','date'],
            'customer_type' => ['required','in:regular,frecuente,mayorista'],
            'discount_percentage' => ['nullable','numeric','min:0','max:100'],
            'status' => ['nullable','boolean'],
        ];
    }
}
