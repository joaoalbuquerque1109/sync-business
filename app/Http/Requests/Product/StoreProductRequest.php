<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:20'],
            'base_price' => ['required', 'numeric'],
            'status' => ['required', 'string', 'max:50'],
            'technical_notes' => ['nullable', 'string'],
        ];
    }
}
