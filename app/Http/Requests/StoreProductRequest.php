<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'base_price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.size' => ['required', 'string', 'max:50'],
            'variants.*.color' => ['required', 'string', 'max:50'],
            'variants.*.current_stock' => ['required', 'integer', 'min:0'],
            'variants.*.low_stock_threshold' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'variants.required' => 'At least one variant is required.',
            'variants.min' => 'At least one variant is required.',
            'variants.*.size.required' => 'Size is required for each variant.',
            'variants.*.color.required' => 'Color is required for each variant.',
            'variants.*.current_stock.required' => 'Stock quantity is required for each variant.',
            'variants.*.current_stock.min' => 'Stock cannot be negative.',
        ];
    }
}
