<div class="p-4 sm:p-6 space-y-4" x-data>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold" style="color: #e0dcd4;">Products</h1>
            <p class="text-xs mt-0.5" style="color: #706860;">Manage products, variants, pricing, and stock.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </button>
    </div>

    @include('livewire.admin.products.filters')

    @include('livewire.admin.products.product-table')

    @include('livewire.admin.products.product-form')

    <x-confirm-delete-modal />
</div>
