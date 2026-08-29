<?php

namespace App\Livewire\Admin;

use App\Models\Discount;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DiscountManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editingDiscount = null;

    public $name = '';

    public $percentage = '';

    public $is_active = false;

    public $starts_at = '';

    public $ends_at = '';

    public $product_id = '';

    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->percentage = '';
        $this->is_active = false;
        $this->starts_at = '';
        $this->ends_at = '';
        $this->product_id = '';
        $this->editingDiscount = null;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Discount $discount): void
    {
        $this->editingDiscount = $discount;
        $this->name = $discount->name;
        $this->percentage = $discount->percentage;
        $this->is_active = $discount->is_active;
        $this->starts_at = $discount->starts_at?->format('Y-m-d\TH:i');
        $this->ends_at = $discount->ends_at?->format('Y-m-d\TH:i');
        $this->product_id = $discount->product_id ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'product_id' => 'nullable|integer|exists:products,id',
        ]);

        // Convert empty string to null for foreign key
        if (empty($validated['product_id'])) {
            $validated['product_id'] = null;
        }

        if ($this->editingDiscount) {
            $this->editingDiscount->update($validated);
            $message = 'Discount updated successfully!';
        } else {
            Discount::create($validated);
            $message = 'Discount created successfully!';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('notify', message: $message);
    }

    public function delete(Discount $discount): void
    {
        $discount->delete();
        $this->dispatch('notify', message: 'Discount deleted successfully!');
    }

    public function toggleActive(Discount $discount): void
    {
        $discount->update(['is_active' => ! $discount->is_active]);
        $this->dispatch('notify', message: 'Discount status updated!');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $discounts = Discount::with('product')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        $products = Product::where('is_active', true)->get(['id', 'name']);

        return view('livewire.admin.discount-management', [
            'discounts' => $discounts,
            'products' => $products,
        ]);
    }
}
