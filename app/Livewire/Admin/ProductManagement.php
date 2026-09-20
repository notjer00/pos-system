<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProductManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $filterCategory = '';

    public $filterStatus = '';

    public $expandedProduct = null;

    public $showModal = false;

    public $editingProduct = null;

    public $name = '';

    public $base_price = '';

    public $category = '';

    public $brand = '';

    public $unit = 'Piece';

    public $is_active = true;

    public $variants = [];

    protected $listeners = ['refreshProducts' => '$refresh'];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->base_price = '';
        $this->category = '';
        $this->brand = '';
        $this->unit = 'Piece';
        $this->is_active = true;
        $this->variants = [
            ['variant_name' => '', 'current_stock' => 0, 'low_stock_threshold' => 5, 'sku' => ''],
        ];
        $this->editingProduct = null;
    }

    #[Computed]
    public function availableCategories(): array
    {
        return Product::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();
    }

    public function toggleExpand(int $productId): void
    {
        $this->expandedProduct = $this->expandedProduct === $productId ? null : $productId;
    }

    public function quickUpdateStock(int $variantId, int $quantity): void
    {
        $variant = ProductVariant::findOrFail($variantId);
        $newStock = max(0, $variant->current_stock + $quantity);
        $variant->update(['current_stock' => $newStock]);
        $this->dispatch('notify', message: 'Stock updated');
    }

    public function setQuickStock(int $variantId, int $value): void
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->update(['current_stock' => max(0, $value)]);
        $this->dispatch('notify', message: 'Stock updated');
    }

    public function addVariant(): void
    {
        $this->variants[] = ['variant_name' => '', 'current_stock' => 0, 'low_stock_threshold' => 5, 'sku' => ''];
    }

    public function removeVariant(int $index): void
    {
        if (count($this->variants) > 1) {
            unset($this->variants[$index]);
            $this->variants = array_values($this->variants);
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Product $product): void
    {
        $this->editingProduct = $product;
        $this->name = $product->name;
        $this->base_price = $product->base_price;
        $this->category = $product->category;
        $this->brand = $product->brand;
        $this->unit = $product->unit ?? 'Piece';
        $this->is_active = $product->is_active;
        $this->variants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'variant_name' => $variant->variant_name,
                'current_stock' => $variant->current_stock,
                'low_stock_threshold' => $variant->low_stock_threshold,
            ];
        })->toArray();
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.variant_name' => 'required|string|max:255',
            'variants.*.current_stock' => 'required|integer|min:0',
            'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $isNewProduct = ! $this->editingProduct;

        DB::transaction(function () use ($validated, $isNewProduct) {
            if (! $isNewProduct) {
                $this->editingProduct->update([
                    'name' => $validated['name'],
                    'base_price' => $validated['base_price'],
                    'category' => $validated['category'],
                    'brand' => $validated['brand'],
                    'unit' => $validated['unit'],
                    'is_active' => $validated['is_active'],
                ]);

                $existingVariantIds = $this->editingProduct->variants->pluck('id')->toArray();
                $submittedVariantIds = collect($validated['variants'])->pluck('id')->filter()->toArray();
                $toDelete = array_diff($existingVariantIds, $submittedVariantIds);
                ProductVariant::whereIn('id', $toDelete)->delete();
            } else {
                $this->editingProduct = Product::create([
                    'name' => $validated['name'],
                    'base_price' => $validated['base_price'],
                    'category' => $validated['category'],
                    'brand' => $validated['brand'],
                    'unit' => $validated['unit'],
                    'is_active' => $validated['is_active'],
                ]);
            }

            foreach ($validated['variants'] as $variantData) {
                $variantId = $variantData['id'] ?? null;
                unset($variantData['id']);

                if (empty($variantData['sku'])) {
                    $tempVariant = new ProductVariant([
                        'product_id' => $this->editingProduct->id,
                        'variant_name' => $variantData['variant_name'],
                    ]);
                    $tempVariant->setRelation('product', $this->editingProduct);
                    $variantData['sku'] = $tempVariant->generateSku();
                }

                if ($variantId) {
                    ProductVariant::where('id', $variantId)->update($variantData);
                } else {
                    $variantData['product_id'] = $this->editingProduct->id;
                    ProductVariant::create($variantData);
                }
            }
        });

        $message = $isNewProduct ? 'Product created successfully!' : 'Product updated successfully!';
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('refreshProducts');
        $this->dispatch('notify', message: $message);
    }

    public function delete(Product $product): void
    {
        $product->delete();
        $this->dispatch('refreshProducts');
        $this->dispatch('notify', message: 'Product deleted successfully!');
    }

    public function toggleActive(Product $product): void
    {
        $product->update(['is_active' => ! $product->is_active]);
        $this->dispatch('notify', message: 'Product status updated!');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $products = Product::with('variants')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%")
                    ->orWhere('brand', 'like', "%{$this->search}%");
            })
            ->when($this->filterCategory !== '', function ($query) {
                $query->where('category', $this->filterCategory);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus === 'active');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.product-management', [
            'products' => $products,
        ]);
    }
}
