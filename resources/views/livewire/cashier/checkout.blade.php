<div class="flex h-[calc(100vh-4rem)] flex-col overflow-hidden" x-data="{ lastCartCount: {{ $cartItemCount }} }" x-effect="if ({{ $cartItemCount }} !== lastCartCount) { lastCartCount = {{ $cartItemCount }}; $el.classList.remove('cart-flash'); void $el.offsetWidth; $el.classList.add('cart-flash'); }">

    {{-- ── Main Content ───────────────────────────────────── --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- ── Left: Product Grid ─────────────────────────── --}}
        <div class="flex flex-1 flex-col overflow-hidden">

            {{-- Search + SKU --}}
            <div class="co-search-bar">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2" style="color: #706860;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search products by name..."
                        class="input-field pl-10"
                    >
                </div>
                <div class="relative w-56">
                    <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2" style="color: #706860;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="7" height="5" x="7" y="7" rx="1"/><rect width="7" height="5" x="10" y="12" rx="1"/></svg>
                    <input
                        type="text"
                        wire:model="skuScan"
                        wire:keydown.enter="scanSku"
                        placeholder="Scan SKU barcode..."
                        class="input-field pl-10"
                        style="border-color: rgba(200,155,60,0.2); background: rgba(200,155,60,0.03);"
                        autocomplete="off"
                        autofocus
                    >
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="flex-1 overflow-y-auto p-6">
                @if ($products->count() > 0)
                    <div class="space-y-6">
                        @foreach ($products as $product)
                            @php
                                $activeVariants = $product->variants->filter(fn ($v) => $v->current_stock > 0);
                                $discount = $product->activeDiscount();
                                $lowestPrice = $activeVariants->min(fn ($v) => $v->product->base_price * (1 - ($discount?->percentage ?? 0) / 100));
                                $highestPrice = $activeVariants->max(fn ($v) => $v->product->base_price * (1 - ($discount?->percentage ?? 0) / 100));
                                $totalStock = $activeVariants->sum('current_stock');
                            @endphp

                            {{-- Product Section --}}
                            <div class="co-product-section">
                            {{-- Product Header --}}
                            <div class="flex items-center gap-4 mb-3">
                                    @if ($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="co-product-img">
                                    @else
                                        <div class="co-product-img-fallback">
                                            <svg class="h-10 w-10" style="color: #d4a850;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>
                                        </div>
                                    @endif
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-sm font-semibold" style="color: #e0dcd4;">{{ $product->name }}</h3>
                                                @if ($discount)
                                                    <span class="badge-warning text-[10px]">{{ $discount->percentage }}% OFF</span>
                                                @endif
                                            </div>
                                            <div class="mt-0.5 flex items-center gap-2 text-xs" style="color: #706860;">
                                                @if ($product->category)
                                                    <span>{{ $product->category }}</span>
                                                    <span>·</span>
                                                @endif
                                                <span>{{ $activeVariants->count() }} variant{{ $activeVariants->count() !== 1 ? 's' : '' }}</span>
                                                <span>·</span>
                                                <span class="{{ $totalStock <= 10 ? 'font-medium' : '' }}" style="color: {{ $totalStock <= 10 ? '#f0a0a0' : '#706860' }}">{{ $totalStock }} in stock</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Variant Grid --}}
                                <div class="co-variant-grid">
                                    @foreach ($activeVariants as $variant)
                                        @php
                                            $variantDiscount = $variant->product->activeDiscount();
                                            $variantFinalPrice = $variant->product->base_price * (1 - ($variantDiscount?->percentage ?? 0) / 100);
                                            $isLowStock = $variant->isLowStock();
                                        @endphp
                                        <button
                                            type="button"
                                            wire:click="addSelectedVariantToCart({{ $variant->id }})"
                                            class="{{ $isLowStock ? 'variant-btn-low-stock' : 'variant-btn' }}"
                                        >
                                            <div class="flex items-center justify-between w-full gap-1">
                                                <span class="font-medium text-foreground truncate min-w-0">{{ $variant->getVariantDisplayName() }}</span>
                                                @if ($isLowStock)
                                                    <span class="h-1.5 w-1.5 rounded-full shrink-0" style="background: #f0a0a0;" title="Low stock"></span>
                                                @endif
                                            </div>
                                            <div class="flex items-baseline gap-1.5 w-full mt-1">
                                                <span class="text-sm font-semibold shrink-0" style="color: {{ $variantDiscount ? '#d4a850' : '#e0dcd4' }};">
                                                    &#8369;{{ number_format($variantFinalPrice, 2) }}
                                                </span>
                                                @if ($variantDiscount)
                                                    <span class="text-[10px] line-through truncate" style="color: #706860;">&#8369;{{ number_format($variant->product->base_price, 2) }}</span>
                                                @endif
                                            </div>
                                            <div class="w-full mt-1.5">
                                                <div class="h-1 overflow-hidden rounded-full" style="background: rgba(255,255,255,0.06);">
                                                    <div class="h-full rounded-full {{ $isLowStock ? 'bg-destructive' : 'bg-primary/40' }}" style="width: {{ min(100, ($variant->current_stock / max($variant->low_stock_threshold * 3, 1)) * 100) }}%"></div>
                                                </div>
                                                <span class="text-[10px] {{ $isLowStock ? 'font-medium' : '' }}" style="color: {{ $isLowStock ? '#f0a0a0' : '#706860' }}">{{ $variant->current_stock }} left</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                            <svg class="h-7 w-7" style="color: #5a5550;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <h3 class="mt-4 text-sm font-semibold" style="color: #e0dcd4;">No products found</h3>
                        <p class="mt-1 text-sm" style="color: #706860;">Try a different search or scan a SKU barcode.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Right: Cart Panel ──────────────────────────── --}}
        <div class="co-panel">

            {{-- Cart Header --}}
            <div class="co-panel-header">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.15);">
                        <svg class="h-4 w-4" style="color: #d4a850;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold" style="color: #e0dcd4;">Current Sale</h2>
                        @if ($cartItemCount > 0)
                            <span class="text-[10px]" style="color: #706860;">{{ $cartItemCount }} item{{ $cartItemCount !== 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                </div>
                @if (!empty($cart))
                    <button wire:click="resetCart" class="btn-ghost text-xs h-8 px-2.5" style="color: #706860;">
                        <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        Clear
                    </button>
                @endif
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto px-5 py-3">
                @if (empty($cart))
                    <div class="co-empty">
                        <div class="co-empty-icon">
                            <svg class="h-7 w-7" style="color: #5a5550;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-medium" style="color: #706860;">Cart is empty</p>
                        <p class="mt-1 text-xs" style="color: #5a5550;">Tap a product to add it</p>
                    </div>
                @else
                    <div class="space-y-1" x-data="{ flashId: null }">
                        @foreach ($cart as $index => $item)
                            <div
                                class="co-cart-item cart-item-enter"
                                x-data
                                x-init="$watch('$wire.cart', () => { if ({{ $index }} === $wire.cart.length - 1) { flashId = {{ $index }}; setTimeout(() => flashId = null, 400); } })"
                            >
                                {{-- Item Image --}}
                                @if (!empty($item['image_path']))
                                    <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['product_name'] }}" class="co-cart-img">
                                @else
                                    <div class="co-cart-img-fallback">
                                        <svg class="h-4 w-4" style="color: #706860;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    </div>
                                @endif

                                {{-- Item Details --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="truncate text-sm font-medium" style="color: #e0dcd4;">{{ $item['product_name'] }}</span>
                                        @if ($item['discount_percentage'] > 0)
                                            <span class="badge-warning shrink-0 text-[9px] px-1 py-0">-{{ $item['discount_percentage'] }}%</span>
                                        @endif
                                    </div>
                                    <div class="mt-0.5 flex items-center gap-1 text-xs" style="color: #706860;">
                                        <span>{{ $item['variant_name'] }}</span>
                                    </div>
                                </div>

                                {{-- Quantity Controls --}}
                                <div class="flex items-center gap-1">
                                    <button
                                        wire:click="updateQuantity({{ $item['variant_id'] }}, {{ $item['quantity'] - 1 }})"
                                        class="btn-icon-sm"
                                        style="color: #706860;"
                                        @if ($item['quantity'] <= 1) disabled @endif
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/></svg>
                                    </button>
                                    <span class="w-7 text-center text-sm font-semibold tabular-nums" style="color: #e0dcd4;">{{ $item['quantity'] }}</span>
                                    <button
                                        wire:click="updateQuantity({{ $item['variant_id'] }}, {{ $item['quantity'] + 1 }})"
                                        class="btn-icon-sm"
                                        style="color: #706860;"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </button>
                                </div>

                                {{-- Subtotal + Remove --}}
                                <div class="flex flex-col items-end gap-0.5">
                                    <span class="text-sm font-semibold tabular-nums" style="color: #e0dcd4;">&#8369;{{ number_format($item['subtotal'], 2) }}</span>
                                    <button
                                        wire:click="removeFromCart({{ $item['variant_id'] }})"
                                        class="co-remove-btn"
                                        title="Remove"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Cart Footer --}}
            @if (!empty($cart))
                <div class="co-footer">
                    {{-- Totals --}}
                    <div class="space-y-2">
                        @php
                            $hasDiscount = collect($cart)->contains(fn ($item) => $item['discount_percentage'] > 0);
                            $originalTotal = collect($cart)->sum(fn ($item) => $item['base_price'] * $item['quantity']);
                        @endphp
                        @if ($hasDiscount)
                            <div class="flex justify-between text-xs" style="color: #706860;">
                                <span>Original</span>
                                <span class="line-through">&#8369;{{ number_format($originalTotal, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between pt-2" style="border-top: 1px solid rgba(255,255,255,0.06);">
                            <span class="text-sm font-medium" style="color: #706860;">Total</span>
                            <span class="text-2xl font-bold tabular-nums" style="color: #e0dcd4;">&#8369;{{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    <button
                        wire:click="openPaymentModal"
                        class="btn-primary-lg w-full"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        Pay &#8369;{{ number_format($cartTotal, 2) }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- ── Payment Modal ───────────────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if ($showPaymentModal)
        <div class="dialog-overlay" wire:click="closePaymentModal"></div>
        <div class="dialog-content max-w-md" x-data="{ method: '{{ $paymentMethod }}' }" x-effect="$wire.set('paymentMethod', method)">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold" style="color: #e0dcd4;">Complete Sale</h3>
                <button wire:click="closePaymentModal" class="btn-icon" style="color: #706860;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="processSale" class="mt-6 space-y-5">
                {{-- Total --}}
                <div class="rounded-xl p-5 text-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                    <span class="text-xs font-medium uppercase tracking-wider" style="color: #706860;">Amount Due</span>
                    <div class="mt-1 text-3xl font-bold tabular-nums" style="color: #e0dcd4;">&#8369;{{ number_format($cartTotal, 2) }}</div>
                </div>

                {{-- Payment Method --}}
                <div class="space-y-2.5">
                    <label class="text-sm font-medium" style="color: #e0dcd4;">Payment Method</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            x-on:click="method = 'cash'"
                            class="flex items-center justify-center gap-2 rounded-xl p-3.5 text-sm font-medium transition-all duration-200"
                            :class="method === 'cash' ? '' : ''"
                            :style="method === 'cash' ? 'background: rgba(200,155,60,0.1); border: 2px solid rgba(200,155,60,0.4); color: #d4a850;' : 'background: rgba(255,255,255,0.03); border: 2px solid rgba(255,255,255,0.06); color: #706860;'"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                            Cash
                        </button>
                        <button
                            type="button"
                            x-on:click="method = 'card'"
                            class="flex items-center justify-center gap-2 rounded-xl p-3.5 text-sm font-medium transition-all duration-200"
                            :style="method === 'card' ? 'background: rgba(200,155,60,0.1); border: 2px solid rgba(200,155,60,0.4); color: #d4a850;' : 'background: rgba(255,255,255,0.03); border: 2px solid rgba(255,255,255,0.06); color: #706860;'"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            Card
                        </button>
                    </div>
                </div>

                {{-- Cash Input --}}
                <div x-show="method === 'cash'" x-transition class="space-y-2.5">
                    <label for="cashReceived" class="text-sm font-medium" style="color: #e0dcd4;">Cash Received</label>
                    <input
                        type="number"
                        id="cashReceived"
                        step="0.01"
                        wire:model.live="cashReceived"
                        class="input-field-lg text-center text-lg font-semibold"
                        required
                    >
                    @error('cashReceived')
                        <p class="text-xs" style="color: #f0a0a0;">{{ $message }}</p>
                    @enderror
                    <div class="rounded-xl p-4 text-center" style="background: {{ $change >= 0 ? 'rgba(200,155,60,0.1)' : 'rgba(180,50,50,0.1)' }}; border: 1px solid {{ $change >= 0 ? 'rgba(200,155,60,0.15)' : 'rgba(180,50,50,0.15)' }};">
                        <span class="text-xs font-medium uppercase tracking-wider" style="color: {{ $change >= 0 ? '#d4a850' : '#f0a0a0' }};">Change</span>
                        <div class="text-2xl font-bold tabular-nums" style="color: {{ $change >= 0 ? '#d4a850' : '#f0a0a0' }};">
                            &#8369;{{ number_format($change, 2) }}
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" wire:click="closePaymentModal" class="btn-secondary flex-1">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn-primary flex-1"
                        :disabled="method === 'cash' && {{ $cashReceived }} < {{ $cartTotal }}"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                        Complete Sale
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- ── Variant Picker Dialog ───────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div x-show="$wire.showVariantPicker" x-cloak style="display: none;" x-transition.opacity.duration.200ms>
        <div class="dialog-overlay" wire:click="closeVariantPicker"></div>
        <div
            class="dialog-content max-w-md"
            x-data
            @click="
                const btn = $event.target.closest('[data-variant-id]');
                if (btn) {
                    $wire.addSelectedVariantToCart(Number(btn.dataset.variantId));
                }
            "
        >
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold" style="color: #e0dcd4;">{{ $selectedProduct?->name }}</h3>
                    <p class="mt-0.5 text-sm" style="color: #706860;">Select a variant to add to cart</p>
                </div>
                <button wire:click="closeVariantPicker" class="btn-icon" style="color: #706860;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            @php
                $pickerDiscount = $selectedProduct?->activeDiscount();
                $pickerVariants = $selectedProduct ? $selectedProduct->variants->filter(fn ($v) => $v->current_stock > 0) : collect();
            @endphp

            <div class="mt-5 grid grid-cols-2 gap-2 max-h-80 overflow-y-auto pr-1">
                @foreach ($pickerVariants as $variant)
                    @php
                        $pPrice = $variant->product->base_price * (1 - ($pickerDiscount?->percentage ?? 0) / 100);
                        $pLow = $variant->isLowStock();
                    @endphp
                    <button
                        type="button"
                        data-variant-id="{{ $variant->id }}"
                        class="{{ $pLow ? 'variant-btn-low-stock' : 'variant-btn' }} justify-between"
                    >
                        <div class="w-full space-y-0.5">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-foreground">{{ $variant->getVariantDisplayName() }}</span>
                                @if ($pLow)
                                    <span class="badge-destructive text-[9px] px-1 py-0">Low</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <span class="text-sm font-semibold" style="color: {{ $pickerDiscount ? '#d4a850' : '#e0dcd4' }};">
                                &#8369;{{ number_format($pPrice, 2) }}
                            </span>
                            <span class="text-[10px] {{ $pLow ? 'text-destructive' : 'text-muted-foreground' }}">
                                {{ $variant->current_stock }} left
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end">
                <button wire:click="closeVariantPicker" class="btn-secondary">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>