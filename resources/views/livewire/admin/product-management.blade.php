<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
        <button wire:click="create" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Product
        </button>
    </div>

    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search products..."
            class="input-field w-full md:w-96"
        >
    </div>

    @if ($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size System</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variants</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                @if ($product->description)
                                    <div class="text-sm text-gray-500 line-clamp-1">{{ $product->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->category ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if ($product->gender === 'male') bg-blue-100 text-blue-800
                                    @elseif ($product->gender === 'female') bg-pink-100 text-pink-800
                                    @else bg-gray-100 text-gray-800 @endif
                                ">
                                    {{ ucfirst($product->gender) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $product->size_system)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">₱{{ number_format($product->base_price, 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($product->variants as $variant)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if ($variant->isLowStock())
                                                bg-red-100 text-red-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif
                                        ">
                                            {{ $variant->getEffectiveSize() }}/{{ $variant->color }}: {{ $variant->current_stock }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->variants->sum('current_stock') }}</td>
                            <td class="px-6 py-4">
                                <button
                                    wire:click="toggleActive({{ $product->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                                        @if ($product->is_active)
                                            bg-green-100 text-green-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                    @if ($product->is_active) Active @else Inactive @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button wire:click="edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="delete({{ $product->id }})" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this product and all its variants?')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
            <button wire:click="create" class="mt-4 btn-primary">Create Product</button>
        </div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $editingProduct ? 'Edit Product' : 'Create Product' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="p-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="name" class="input-field w-full" required>
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Base Price *</label>
                                <input type="number" step="0.01" wire:model="base_price" class="input-field w-full" required>
                                @error('base_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <input type="text" wire:model="category" class="input-field w-full">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                                <select wire:model="gender" class="input-field w-full" required>
                                    <option value="unisex">Unisex</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Size System *</label>
                                <select wire:model.live="size_system" class="input-field w-full" required>
                                    <option value="apparel">Apparel (XS, S, M, L, XL, XXL)</option>
                                    <option value="us_footwear">US Footwear (4-14)</option>
                                    <option value="eu_footwear">EU Footwear (35-48)</option>
                                    <option value="uk_footwear">UK Footwear (3-13)</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="3" class="input-field w-full"></textarea>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-medium text-gray-900">Variants</h4>
                                <button type="button" wire:click="addVariant" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Variant</button>
                            </div>

                            @foreach ($variants as $index => $variant)
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-3 p-3 bg-gray-50 rounded-lg">
                                    <input type="hidden" wire:model="variants.{{ $index }}.id">
                                    
                                    @if ($this->isFootwear())
                                        <div class="md:col-span-1">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Footwear Size *</label>
                                            <select wire:model="variants.{{ $index }}.footwear_size" class="input-field w-full" required>
                                                <option value="">Select size</option>
                                                @foreach ($this->getCurrentSizeOptions() as $size)
                                                    <option value="{{ $size }}">{{ $size }}</option>
                                                @endforeach
                                            </select>
                                            @error("variants.{$index}.footwear_size") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            <input type="hidden" wire:model="variants.{{ $index }}.size">
                                        </div>
                                    @else
                                        <div class="md:col-span-1">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Size *</label>
                                            <select wire:model="variants.{{ $index }}.size" class="input-field w-full" required>
                                                <option value="">Select size</option>
                                                @foreach ($this->getCurrentSizeOptions() as $size)
                                                    <option value="{{ $size }}">{{ $size }}</option>
                                                @endforeach
                                            </select>
                                            @error("variants.{$index}.size") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            <input type="hidden" wire:model="variants.{{ $index }}.footwear_size">
                                        </div>
                                    @endif
                                    
                                    <div class="md:col-span-1">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Color *</label>
                                        <select wire:model="variants.{{ $index }}.color" class="input-field w-full" required>
                                            <option value="">Select color</option>
                                            @foreach ($variantColors as $color)
                                                <option value="{{ $color }}">{{ $color }}</option>
                                            @endforeach
                                        </select>
                                        @error("variants.{$index}.color") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                                        <input type="text" wire:model="variants.{{ $index }}.sku" class="input-field w-full" placeholder="Auto-generated" readonly>
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Stock *</label>
                                        <input type="number" min="0" wire:model="variants.{{ $index }}.current_stock" class="input-field w-full" required>
                                        @error("variants.{$index}.current_stock") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Low Stock Threshold</label>
                                        <input type="number" min="0" wire:model="variants.{{ $index }}.low_stock_threshold" class="input-field w-full">
                                    </div>
                                    <div class="md:col-span-1 flex items-end">
                                        <button type="button" wire:click="removeVariant({{ $index }})" class="text-red-600 hover:text-red-900 text-sm" @if (count($variants) <= 1) disabled @endif>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end space-x-3 border-t pt-4">
                            <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary">
                                {{ $editingProduct ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>