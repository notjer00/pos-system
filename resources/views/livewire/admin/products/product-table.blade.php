@if ($products->count() > 0)
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-border">
            <thead>
                <tr class="bg-muted/50">
                    <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Variants</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach ($products as $product)
                    @include('livewire.admin.products.product-row')
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@else
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
