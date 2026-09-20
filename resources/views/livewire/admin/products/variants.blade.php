<tr>
    <td colspan="7" class="px-4 py-0">
        <div class="py-3 pl-8">
            @if ($product->variants->count() > 0)
                <div class="border border-border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-border">
                        <thead>
                            <tr class="bg-muted/30">
                                <th class="px-3 py-2 text-left text-xs font-medium text-muted-foreground">Variant</th>
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
                                    <td class="px-3 py-2 text-sm text-foreground font-medium">{{ $variant->getVariantDisplayName() }}</td>
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
