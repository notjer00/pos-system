<tr>
    <td colspan="7" style="padding: 0;">
        <div class="py-3 pl-8">
            @if ($product->variants->count() > 0)
                <div class="card overflow-hidden">
                    <table class="min-w-full pm-subtable">
                        <thead>
                            <tr>
                                <th>Variant</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Threshold</th>
                                <th>Status</th>
                                <th class="text-right">Adjust</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->variants as $variant)
                                @php
                                    $isLow = $variant->current_stock <= $variant->low_stock_threshold;
                                    $maxForBar = max($variant->low_stock_threshold * 3, $variant->current_stock, 1);
                                    $stockPercent = min(($variant->current_stock / $maxForBar) * 100, 100);
                                @endphp
                                <tr>
                                    <td class="font-medium" style="color: #e0dcd4;">{{ $variant->getVariantDisplayName() }}</td>
                                    <td class="font-mono text-xs" style="color: #706860;">{{ $variant->sku }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="pm-stock-bar">
                                                <div
                                                    class="pm-stock-bar-fill {{ $isLow ? 'pm-stock-danger' : 'pm-stock-ok' }}"
                                                    style="width: {{ $stockPercent }}%"
                                                ></div>
                                            </div>
                                            <span class="{{ $isLow ? 'font-medium' : '' }}" style="{{ $isLow ? 'color: #f0a0a0;' : 'color: #e0dcd4;' }}">{{ $variant->current_stock }}</span>
                                        </div>
                                    </td>
                                    <td style="color: #807870;">{{ $variant->low_stock_threshold }}</td>
                                    <td>
                                        @if ($isLow)
                                            <span class="badge-destructive">Low Stock</span>
                                        @else
                                            <span class="badge-success">OK</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-end gap-1">
                                            <button
                                                wire:click="quickUpdateStock({{ $variant->id }}, -1)"
                                                class="btn-ghost btn-icon-sm"
                                                style="width: 1.5rem; height: 1.5rem;"
                                                @if ($variant->current_stock <= 0) disabled @endif
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            <button
                                                wire:click="quickUpdateStock({{ $variant->id }}, 1)"
                                                class="btn-ghost btn-icon-sm"
                                                style="width: 1.5rem; height: 1.5rem;"
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
                <p class="text-sm py-2" style="color: #706860;">No variants yet. Click Edit to add variants.</p>
            @endif
        </div>
    </td>
</tr>
