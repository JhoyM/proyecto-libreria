<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'code' => ['required','string','max:50', Rule::unique('products','code')->ignore($productId)],
            'name' => ['required','string','max:200'],
            'description' => ['nullable','string'],
            'category_id' => ['required','integer','exists:categories,id'],
            'supplier_id' => ['nullable','integer','exists:suppliers,id'],
            'purchase_price' => ['nullable','numeric','min:0'],
            'sale_price' => ['required','numeric','min:0'],
            'stock' => ['nullable','integer','min:0'],
            'min_stock' => ['nullable','integer','min:0'],
            'author' => ['nullable','string','max:150'],
            'publisher' => ['nullable','string','max:100'],
            'isbn' => ['nullable','string','max:20'],
            'publication_year' => ['nullable','integer','digits:4'],
            'genre' => ['nullable','string','max:100'],
            'pages' => ['nullable','integer','min:1'],
            'language' => ['nullable','string','max:50'],
            'brand' => ['nullable','string','max:100'],
            'model' => ['nullable','string','max:100'],
            'color' => ['nullable','string','max:50'],
            'size' => ['nullable','string','max:50'],
            'image_path' => ['nullable','string','max:255'],
            'status' => ['sometimes','boolean'],
        ];
    }
}
