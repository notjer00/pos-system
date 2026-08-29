<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProductManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editingProduct = null;

    public $name = '';

    public $base_price = '';

    public $category = '';

    public $description = '';

    public $is_active = true;

    public $gender = 'unisex';

    public $size_system = 'apparel';

    public $variants = [];

    public $variantColors = ['Black', 'White', 'Gray', 'Navy', 'Blue', 'Red', 'Green', 'Pink', 'Yellow', 'Purple', 'Orange', 'Floral', 'Solid Blue'];

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
        $this->description = '';
        $this->is_active = true;
        $this->gender = 'unisex';
        $this->size_system = 'apparel';
        $this->variants = [
            ['size' => '', 'footwear_size' => '', 'color' => '', 'current_stock' => 0, 'low_stock_threshold' => 5, 'sku' => ''],
        ];
        $this->editingProduct = null;
    }

    public function addVariant(): void
    {
        $this->variants[] = ['size' => '', 'footwear_size' => '', 'color' => '', 'current_stock' => 0, 'low_stock_threshold' => 5, 'sku' => ''];
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
        $this->description = $product->description;
        $this->is_active = $product->is_active;
        $this->gender = $product->gender;
        $this->size_system = $product->size_system;
        $this->variants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'size' => $variant->size,
                'footwear_size' => $variant->footwear_size,
                'color' => $variant->color,
                'current_stock' => $variant->current_stock,
                'low_stock_threshold' => $variant->low_stock_threshold,
            ];
        })->toArray();
        $this->showModal = true;
    }

    public function getCurrentSizeOptions(): array
    {
        // Use the product's size_system to determine options
        $tempProduct = new Product(['size_system' => $this->size_system]);

        return $tempProduct->getSizeOptions();
    }

    public function isFootwear(): bool
    {
        return in_array($this->size_system, ['us_footwear', 'eu_footwear', 'uk_footwear']);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'gender' => 'required|in:male,female,unisex',
            'size_system' => 'required|in:apparel,us_footwear,eu_footwear,uk_footwear',
            'variants' => 'required|array|min:1',
            'variants.*.size' => $this->isFootwear() ? 'nullable|string|max:50' : 'required|string|max:50',
            'variants.*.footwear_size' => $this->isFootwear() ? 'required|string|max:50' : 'nullable|string|max:50',
            'variants.*.color' => 'required|string|max:50',
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
                    'description' => $validated['description'],
                    'is_active' => $validated['is_active'],
                    'gender' => $validated['gender'],
                    'size_system' => $validated['size_system'],
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
                    'description' => $validated['description'],
                    'is_active' => $validated['is_active'],
                    'gender' => $validated['gender'],
                    'size_system' => $validated['size_system'],
                ]);
            }

            foreach ($validated['variants'] as $variantData) {
                $variantId = $variantData['id'] ?? null;
                unset($variantData['id']);

                // Auto-generate SKU using the model's method
                if (empty($variantData['sku'])) {
                    $tempVariant = new ProductVariant([
                        'product_id' => $this->editingProduct->id,
                        'size' => $variantData['size'] ?? '',
                        'footwear_size' => $variantData['footwear_size'] ?? '',
                        'color' => $variantData['color'],
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
                    ->orWhere('category', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.product-management', [
            'products' => $products,
        ]);
    }
}
