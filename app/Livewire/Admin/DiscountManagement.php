<?php

namespace App\Livewire\Admin;

use App\Models\Discount;
use App\Models\Product;
use App\Models\User;
use Livewire\Attributes\Computed;
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

        $validated['starts_at'] = $validated['starts_at'] ?: null;
        $validated['ends_at'] = $validated['ends_at'] ?: null;

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

    #[Computed]
    public function commissionPreview(): ?array
    {
        $pct = (float) $this->percentage;
        if ($pct <= 0 || $pct > 100) {
            return null;
        }

        $cashiers = User::where('role', 'cashier')->where('commission_rate', '>', 0)->get();

        if ($cashiers->isEmpty()) {
            return null;
        }

        if (! empty($this->product_id)) {
            $product = Product::find($this->product_id);
            if (! $product) {
                return null;
            }

            $basePrice = $product->base_price;
            $discountedPrice = $basePrice * (1 - $pct / 100);

            $previews = $cashiers->map(fn (User $cashier) => [
                'cashier_name' => $cashier->name,
                'commission_rate' => $cashier->commission_rate,
                'base_commission' => round($basePrice * ($cashier->commission_rate / 100), 2),
                'discounted_commission' => round($discountedPrice * ($cashier->commission_rate / 100), 2),
                'commission_loss' => round(($basePrice - $discountedPrice) * ($cashier->commission_rate / 100), 2),
            ])->toArray();
        } else {
            $sampleProducts = Product::where('is_active', true)->take(3)->get(['id', 'name', 'base_price']);

            $previews = $cashiers->map(function (User $cashier) use ($pct, $sampleProducts) {
                $examples = $sampleProducts->map(function (Product $product) use ($pct) {
                    $discounted = $product->base_price * (1 - $pct / 100);

                    return [
                        'product_name' => $product->name,
                        'base_price' => $product->base_price,
                        'discounted_price' => round($discounted, 2),
                    ];
                })->toArray();

                $avgBase = $examples ? $examples[0]['base_price'] : 0;
                $avgDiscounted = $examples ? $examples[0]['discounted_price'] : 0;

                return [
                    'cashier_name' => $cashier->name,
                    'commission_rate' => $cashier->commission_rate,
                    'base_commission' => round($avgBase * ($cashier->commission_rate / 100), 2),
                    'discounted_commission' => round($avgDiscounted * ($cashier->commission_rate / 100), 2),
                    'commission_loss' => round(($avgBase - $avgDiscounted) * ($cashier->commission_rate / 100), 2),
                    'examples' => $examples,
                ];
            })->toArray();
        }

        return $previews;
    }

    public function render()
    {
        $discounts = Discount::with('product')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        $products = Product::where('is_active', true)->get(['id', 'name', 'base_price']);

        return view('livewire.admin.discount-management', [
            'discounts' => $discounts,
            'products' => $products,
            'commissionPreview' => $this->commissionPreview,
        ]);
    }
}
