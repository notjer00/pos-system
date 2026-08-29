<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCashier() ?? false;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required for checkout.',
            'items.min' => 'At least one item is required for checkout.',
            'items.*.product_variant_id.required' => 'Product variant is required.',
            'items.*.product_variant_id.exists' => 'Selected product variant does not exist.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('items', []) as $index => $item) {
                $variant = ProductVariant::find($item['product_variant_id']);
                if ($variant && $variant->current_stock < $item['quantity']) {
                    $validator->errors()->add(
                        "items.$index.quantity",
                        "Insufficient stock for {$variant->product->name} ({$variant->size}/{$variant->color}). Available: {$variant->current_stock}"
                    );
                }
            }
        });
    }
}
