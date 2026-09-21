<tr class="{{ $expandedProduct === $product->id ? 'pm-row-expanded' : '' }}">
    <td>
        <div class="flex items-center gap-3">
            <button
                wire:click="toggleExpand({{ $product->id }})"
                class="pm-expand-btn"
            >
                @if ($expandedProduct === $product->id)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @endif
            </button>
            @if ($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="pm-thumb">
            @else
                <div class="pm-thumb-fallback">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            @endif
            <div class="min-w-0">
                <div class="text-sm font-medium" style="color: #e0dcd4;">{{ $product->name }}</div>
                @if ($product->brand)
                    <div class="text-xs mt-0.5" style="color: #706860;">{{ $product->brand }}</div>
                @endif
            </div>
        </div>
    </td>
    <td style="color: #807870;">{{ $product->category ?? '-' }}</td>
    <td class="font-medium" style="color: #e0dcd4;">&#8369;{{ number_format($product->base_price, 2) }}</td>
    <td style="color: #807870;">{{ $product->variants->count() }}</td>
    <td>
        @php
            $totalStock = $product->variants->sum('current_stock');
            $hasLowStock = $product->variants->contains(fn($v) => $v->current_stock <= $v->low_stock_threshold);
        @endphp
        <div class="flex items-center gap-2">
            <span style="color: #e0dcd4;">{{ $totalStock }}</span>
            @if ($hasLowStock)
                <span class="relative flex h-1.5 w-1.5 shrink-0">
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: #d4a850;"></span>
                </span>
            @endif
        </div>
    </td>
    <td>
        <div class="flex items-center gap-1.5">
            <span class="relative flex h-1.5 w-1.5 shrink-0">
                @if ($product->is_active)
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: #5cc980;"></span>
                @else
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: #706860;"></span>
                @endif
            </span>
            <span class="text-xs font-medium" @if ($product->is_active) style="color: #5cc980;" @else style="color: #706860;" @endif>
                {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </td>
    <td>
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
                class="btn-ghost btn-icon-sm"
                style="color: #f0a0a0;"
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
