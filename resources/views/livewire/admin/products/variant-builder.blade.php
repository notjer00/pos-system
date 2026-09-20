<div class="border-t border-border pt-4">
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-sm font-semibold text-foreground">Variants</h4>
        <button type="button" wire:click="addVariant" class="text-sm text-primary hover:text-primary/80 font-medium">
            + Add Variant
        </button>
    </div>

    @error('variants') <p class="mb-2 text-xs text-destructive">{{ $message }}</p> @enderror

    @foreach ($variants as $index => $variant)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-2 p-3 bg-muted/30 rounded-lg border border-border">
            <input type="hidden" wire:model="variants.{{ $index }}.id">

            <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-medium text-muted-foreground mb-1">Variant Name *</label>
                <input type="text" wire:model="variants.{{ $index }}.variant_name" class="input-field w-full text-sm" placeholder="e.g. Chocolate / 60g">
                @error("variants.{$index}.variant_name") <p class="mt-1 text-xs text-destructive">{{ $message }}</p> @enderror
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

            <div class="col-span-2 md:col-span-4 flex justify-end">
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
