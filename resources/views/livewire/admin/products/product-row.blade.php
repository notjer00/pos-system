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
            @if ($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-16 h-16 shrink-0 rounded-lg object-cover border border-border">
            @else
                <div class="flex w-16 h-16 shrink-0 items-center justify-center rounded-lg bg-muted border border-border">
                    <svg class="w-7 h-7 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            @endif
            <div>
                <div class="text-sm font-medium text-foreground">{{ $product->name }}</div>
                @if ($product->brand)
                    <div class="text-xs text-muted-foreground mt-0.5">{{ $product->brand }}</div>
                @endif
            </div>
        </div>
    </td>
    <td class="px-4 py-3 text-sm text-muted-foreground">{{ $product->category ?? '-' }}</td>
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
        <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2 shrink-0">
                @if ($product->is_active)
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #5cbfaa;"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2" style="background: #5cbfaa;"></span>
                @else
                    <span class="relative inline-flex rounded-full h-2 w-2" style="background: #6a6560;"></span>
                @endif
            </span>
            <span class="text-xs font-medium" @if ($product->is_active) style="color: #5cbfaa;" @else style="color: #6a6560;" @endif>
                {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
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
                @click.prevent="$dispatch('confirm-delete', { id: {{ $product->id }}, title: 'Delete Product?', message: 'Are you sure you want to delete this product and all its variants? This action cannot be undone.' })"
                class="btn-ghost btn-icon-sm text-destructive hover:text-destructive"
                title="Delete product"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </td>
</tr>

@if ($expandedProduct === $product->id)
    @include('livewire.admin.products.variants')
@endif
