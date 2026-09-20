@if ($showModal)
    <div class="dialog-overlay" wire:click="closeModal">
        <div class="dialog-content max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-4 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">
                    {{ $editingProduct ? 'Edit Product' : 'Create Product' }}
                </h3>
                <button wire:click="closeModal" class="btn-ghost btn-icon-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-4 space-y-4">
                @include('livewire.admin.products.product-fields')

                @include('livewire.admin.products.variant-builder')

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
