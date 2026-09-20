@if ($products->count() > 0)
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full pm-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Variants</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @include('livewire.admin.products.product-row')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@else
    <div class="pm-empty card">
        <svg class="pm-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <h3 class="text-sm font-medium" style="color: #e0dcd4;">No products found</h3>
        <p class="text-xs mt-1" style="color: #706860;">Try changing your search or filters.</p>
        <button wire:click="create" class="mt-4 btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </button>
    </div>
@endif
