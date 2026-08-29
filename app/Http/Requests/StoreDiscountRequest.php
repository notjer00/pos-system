<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'percentage.min' => 'Discount percentage cannot be negative.',
            'percentage.max' => 'Discount percentage cannot exceed 100%.',
            'ends_at.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }
}
