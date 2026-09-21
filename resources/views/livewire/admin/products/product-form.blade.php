@if ($showModal)
    <div class="dialog-overlay" wire:click="closeModal">
        <div class="dialog-content max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <h3 class="text-base font-semibold" style="color: #e0dcd4;">
                    {{ $editingProduct ? 'Edit Product' : 'Create Product' }}
                </h3>
                <button wire:click="closeModal" class="btn-ghost btn-icon-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="pt-4 space-y-4">
                @include('livewire.admin.products.product-fields')

                @include('livewire.admin.products.variant-builder')

                <div class="flex justify-end gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">
                        {{ $editingProduct ? 'Update Product' : 'Create Product' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
