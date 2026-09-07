<div class="p-6">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-foreground">Product Management</h1>
        <button wire:click="create" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </button>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-wrap gap-3">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search products..."
            class="input-field w-full md:w-64"
        >
        <select wire:model.live="filterCategory" class="input-field w-full md:w-44">
            <option value="">All Categories</option>
            @foreach ($this->availableCategories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterGender" class="input-field w-full md:w-36">
            <option value="">All Genders</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="unisex">Unisex</option>
        </select>
        <select wire:model.live="filterStatus" class="input-field w-full md:w-36">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    {{-- Products Table --}}
    @if ($products->count() > 0)
        <div class="card overflow-hidden">
            <table class="min-w-full divide-y divide-border">
                <thead>
                    <tr class="bg-muted/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Gender</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Variants</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($products as $product)
                        {{-- Main Row --}}
                        <tr class="hover:bg-muted/30 {{ $expandedProduct === $product->id ? 'bg-muted/20' : '' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <button
                                        wire:click="toggleExpand({{ $product->id }})"
                                        class="shrink-0 w-5 h-5 flex items-center justify-center text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        @if ($expandedProduct === $product->id)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        @endif
                                    </button>
                                    <div>
                                        <div class="text-sm font-medium text-foreground">{{ $product->name }}</div>
                                        @if ($product->description)
                                            <div class="text-xs text-muted-foreground line-clamp-1 mt-0.5">{{ $product->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-muted-foreground">{{ $product->category ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ match($product->gender) { 'male' => 'badge-primary', 'female' => 'badge-secondary', default => '' } }}">
                                    {{ ucfirst($product->gender) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-foreground">₱{{ number_format($product->base_price, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-muted-foreground">{{ $product->variants->count() }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $totalStock = $product->variants->sum('current_stock');
                                    $hasLowStock = $product->variants->contains(fn($v) => $v->current_stock <= $v->low_stock_threshold);
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-foreground">{{ $totalStock }}</span>
                                    @if ($hasLowStock)
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-destructive opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-destructive"></span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <button
                                    wire:click="toggleActive({{ $product->id }})"
                                    class="toggle-switch {{ $product->is_active ? 'active' : '' }}"
                                >
                                    <span class="toggle-switch-knob"></span>
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        wire:click="edit({{ $product->id }})"
                                        class="btn-ghost btn-icon-sm"
                                        title="Edit product"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button
                                        wire:click="delete({{ $product->id }})"
                                        class="btn-ghost btn-icon-sm text-destructive hover:text-destructive"
                                        title="Delete product"
                                        onclick="return confirm('Delete this product and all its variants?')"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Expanded Variants --}}
                        @if ($expandedProduct === $product->id)
                            <tr>
                                <td colspan="8" class="px-4 py-0">
                                    <div class="py-3 pl-8">
                                        @if ($product->variants->count() > 0)
                                            <div class="border border-border rounded-lg overflow-hidden">
                                                <table class="min-w-full divide-y divide-border">
                                                    <thead>
                                                        <tr class="bg-muted/30">
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">Size</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">Color</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">SKU</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">Stock</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">Threshold</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">Status</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-muted-foreground">Adjust</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-border">
                                                        @foreach ($product->variants as $variant)
                                                            @php
                                                                $isLow = $variant->current_stock <= $variant->low_stock_threshold;
                                                                $maxForBar = max($variant->low_stock_threshold * 3, $variant->current_stock, 1);
                                                                $stockPercent = min(($variant->current_stock / $maxForBar) * 100, 100);
                                                            @endphp
                                                            <tr class="hover:bg-muted/20">
                                                                <td class="px-3 py-2 text-sm text-foreground font-medium">{{ $variant->getEffectiveSize() }}</td>
                                                                <td class="px-3 py-2 text-sm text-muted-foreground">{{ $variant->color }}</td>
                                                                <td class="px-3 py-2 text-xs text-muted-foreground font-mono">{{ $variant->sku }}</td>
                                                                <td class="px-3 py-2">
                                                                    <div class="flex items-center gap-2">
                                                                        <div class="w-16 h-1.5 bg-muted rounded-full overflow-hidden">
                                                                            <div
                                                                                class="h-full rounded-full transition-all {{ $isLow ? 'bg-destructive' : 'bg-success' }}"
                                                                                style="width: {{ $stockPercent }}%"
                                                                            ></div>
                                                                        </div>
                                                                        <span class="text-sm {{ $isLow ? 'text-destructive font-medium' : 'text-foreground' }}">{{ $variant->current_stock }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="px-3 py-2 text-sm text-muted-foreground">{{ $variant->low_stock_threshold }}</td>
                                                                <td class="px-3 py-2">
                                                                    @if ($isLow)
                                                                        <span class="badge badge-destructive">Low Stock</span>
                                                                    @else
                                                                        <span class="badge badge-success">OK</span>
                                                                    @endif
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    <div class="flex items-center justify-end gap-1">
                                                                        <button
                                                                            wire:click="quickUpdateStock({{ $variant->id }}, -1)"
                                                                            class="btn-ghost btn-icon-sm text-xs w-6 h-6"
                                                                            @if ($variant->current_stock <= 0) disabled @endif
                                                                        >
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                                                        </button>
                                                                        <button
                                                                            wire:click="quickUpdateStock({{ $variant->id }}, 1)"
                                                                            class="btn-ghost btn-icon-sm text-xs w-6 h-6"
                                                                        >
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-sm text-muted-foreground py-2">No variants yet. Click Edit to add variants.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="card p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="mt-3 text-sm font-medium text-foreground">No products found</h3>
            <p class="mt-1 text-sm text-muted-foreground">Get started by creating your first product.</p>
            <button wire:click="create" class="mt-4 btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </button>
        </div>
    @endif

    {{-- Product Add/Edit Dialog --}}
    @if ($showModal)
        <div class="dialog-overlay" wire:click="closeModal">
            <div class="dialog-content max-w-3xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-foreground">
                        {{ $editingProduct ? 'Edit Product' : 'Create Product' }}
                    </h3>
                    <button wire:click="closeModal" class="btn-ghost btn-icon-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-4 space-y-4">
                    {{-- Product Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Name *</label>
                            <input type="text" wire:model="name" class="input-field w-full">
                            @error('name') <p class="mt-1 text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Base Price *</label>
                            <input type="number" step="0.01" wire:model="base_price" class="input-field w-full">
                            @error('base_price') <p class="mt-1 text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Category</label>
                            <input type="text" wire:model="category" class="input-field w-full" list="category-list">
                            <datalist id="category-list">
                                @foreach ($this->availableCategories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Gender *</label>
                            <select wire:model="gender" class="input-field w-full">
                                <option value="unisex">Unisex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Size System *</label>
                            <select wire:model.live="size_system" class="input-field w-full">
                                <option value="apparel">Apparel (XS - XXL)</option>
                                <option value="us_footwear">US Footwear (4 - 14)</option>
                                <option value="eu_footwear">EU Footwear (35 - 48)</option>
                                <option value="uk_footwear">UK Footwear (3 - 13)</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <button
                                    type="button"
                                    wire:click="$set('is_active', {{ $is_active ? 'false' : 'true' }})"
                                    class="toggle-switch {{ $is_active ? 'active' : '' }}"
                                >
                                    <span class="toggle-switch-knob"></span>
                                </button>
                                <span class="text-sm text-foreground">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="input-field w-full"></textarea>
                    </div>

                    {{-- Variant Builder --}}
                    <div class="border-t border-border pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-foreground">Variants</h4>
                            <button type="button" wire:click="addVariant" class="text-sm text-primary hover:text-primary/80 font-medium">
                                + Add Variant
                            </button>
                        </div>

                        @error('variants') <p class="mb-2 text-xs text-destructive">{{ $message }}</p> @enderror

                        @foreach ($variants as $index => $variant)
                            <div class="grid grid-cols-2 md:grid-cols-7 gap-2 mb-2 p-3 bg-muted/30 rounded-lg border border-border">
                                <input type="hidden" wire:model="variants.{{ $index }}.id">

                                @if ($this->isFootwear())
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">Size *</label>
                                        <select wire:model="variants.{{ $index }}.footwear_size" class="input-field w-full text-sm">
                                            <option value="">-</option>
                                            @foreach ($this->getCurrentSizeOptions() as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" wire:model="variants.{{ $index }}.size">
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">Size *</label>
                                        <select wire:model="variants.{{ $index }}.size" class="input-field w-full text-sm">
                                            <option value="">-</option>
                                            @foreach ($this->getCurrentSizeOptions() as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" wire:model="variants.{{ $index }}.footwear_size">
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-xs font-medium text-muted-foreground mb-1">Color *</label>
                                    <select wire:model="variants.{{ $index }}.color" class="input-field w-full text-sm">
                                        <option value="">-</option>
                                        @foreach ($this->availableColors as $color)
                                            <option value="{{ $color }}">{{ $color }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-muted-foreground mb-1">SKU</label>
                                    <input type="text" wire:model="variants.{{ $index }}.sku" class="input-field w-full text-sm" placeholder="Auto" readonly>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-muted-foreground mb-1">Stock *</label>
                                    <input type="number" min="0" wire:model="variants.{{ $index }}.current_stock" class="input-field w-full text-sm">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-muted-foreground mb-1">Low Stock At</label>
                                    <input type="number" min="0" wire:model="variants.{{ $index }}.low_stock_threshold" class="input-field w-full text-sm">
                                </div>

                                <div class="flex items-end">
                                    <button
                                        type="button"
                                        wire:click="removeVariant({{ $index }})"
                                        class="btn-ghost btn-icon-sm text-destructive hover:text-destructive"
                                        @if (count($variants) <= 1) disabled @endif
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Dialog Footer --}}
                    <div class="flex justify-end gap-3 border-t border-border pt-4">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">
                            {{ $editingProduct ? 'Update Product' : 'Create Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
