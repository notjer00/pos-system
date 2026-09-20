<div class="p-6" x-data>

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-foreground">Product Management</h1>
        <button wire:click="create" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </button>
    </div>

    @include('livewire.admin.products.filters')

    @include('livewire.admin.products.product-table')

    @include('livewire.admin.products.product-form')

    <x-confirm-delete-modal />
</div>
