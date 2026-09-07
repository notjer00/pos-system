<div class="flex h-[calc(100vh-4rem)] flex-col overflow-hidden" x-data="{ lastCartCount: {{ $cartItemCount }} }" x-effect="if ({{ $cartItemCount }} !== lastCartCount) { lastCartCount = {{ $cartItemCount }}; $el.classList.remove('cart-flash'); void $el.offsetWidth; $el.classList.add('cart-flash'); }">

    {{-- ── Top Bar ────────────────────────────────────────── --}}
    <div class="flex items-center justify-between border-b border-border px-6 py-3">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            </div>
            <h1 class="text-lg font-semibold text-foreground">Checkout</h1>
        </div>
        <div class="flex items-center gap-2 text-sm text-muted-foreground">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>{{ Auth::user()->name }}</span>
        </div>
    </div>

    {{-- ── Main Content ───────────────────────────────────── --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- ── Left: Product Grid ─────────────────────────── --}}
        <div class="flex flex-1 flex-col overflow-hidden border-r border-border">

            {{-- Search + SKU --}}
            <div class="flex gap-3 border-b border-border bg-card px-6 py-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search products..."
                        class="input-field pl-9"
                    >
                </div>
                <div class="relative w-56">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><rect width="7" height="5" x="7" y="7" rx="1"/><rect width="7" height="5" x="10" y="12" rx="1"/></svg>
                    <input
                        type="text"
                        wire:model="skuScan"
                        wire:keydown.enter="scanSku"
                        placeholder="Scan SKU..."
                        class="input-field pl-9 bg-warning/5 border-warning/30 focus-visible:ring-warning"
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
                            <div class="space-y-3">
                                {{-- Product Header --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                                            <svg class="h-5 w-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-sm font-semibold text-foreground">{{ $product->name }}</h3>
                                                @if ($discount)
                                                    <span class="badge-warning text-[10px]">{{ $discount->percentage }}% OFF</span>
                                                @endif
                                            </div>
                                            <div class="mt-0.5 flex items-center gap-2 text-xs text-muted-foreground">
                                                @if ($product->category)
                                                    <span>{{ $product->category }}</span>
                                                    <span>·</span>
                                                @endif
                                                <span>{{ $activeVariants->count() }} variant{{ $activeVariants->count() !== 1 ? 's' : '' }}</span>
                                                <span>·</span>
                                                <span class="{{ $totalStock <= 10 ? 'text-destructive font-medium' : '' }}">{{ $totalStock }} in stock</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($product->gender && $product->gender !== 'unisex')
                                        <span class="badge text-[10px]">{{ ucfirst($product->gender) }}</span>
                                    @endif
                                </div>

                                {{-- Variant Grid --}}
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                                    @foreach ($activeVariants as $variant)
                                        @php
                                            $variantDiscount = $variant->product->activeDiscount();
                                            $variantFinalPrice = $variant->product->base_price * (1 - ($variantDiscount?->percentage ?? 0) / 100);
                                            $isLowStock = $variant->isLowStock();
                                        @endphp
                                        <button
                                            wire:click="selectProduct({{ $product->id }})"
                                            class="{{ $isLowStock ? 'variant-btn-low-stock' : 'variant-btn' }}"
                                        >
                                            <div class="flex h-10 w-full items-center justify-center rounded-md bg-muted/50">
                                                <svg class="h-5 w-5 text-muted-foreground/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            </div>
                                            <div class="w-full space-y-0.5">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium text-foreground">{{ $variant->getEffectiveSize() }}</span>
                                                    @if ($isLowStock)
                                                        <span class="h-1.5 w-1.5 rounded-full bg-destructive" title="Low stock"></span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-muted-foreground">{{ $variant->color }}</div>
                                            </div>
                                            <div class="flex items-center justify-between w-full">
                                                <span class="text-sm font-semibold {{ $variantDiscount ? 'text-primary' : 'text-foreground' }}">
                                                    ₱{{ number_format($variantFinalPrice, 2) }}
                                                </span>
                                                @if ($variantDiscount)
                                                    <span class="text-[10px] text-muted-foreground line-through">₱{{ number_format($variant->product->base_price, 2) }}</span>
                                                @endif
                                            </div>
                                            <div class="w-full">
                                                <div class="h-1 overflow-hidden rounded-full bg-muted">
                                                    <div class="h-full rounded-full {{ $isLowStock ? 'bg-destructive' : 'bg-primary/40' }}" style="width: {{ min(100, ($variant->current_stock / max($variant->low_stock_threshold * 3, 1)) * 100) }}%"></div>
                                                </div>
                                                <span class="text-[10px] {{ $isLowStock ? 'text-destructive font-medium' : 'text-muted-foreground' }}">{{ $variant->current_stock }} left</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                            <svg class="h-8 w-8 text-muted-foreground/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <h3 class="mt-4 text-sm font-semibold text-foreground">No products found</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Try a different search or scan a SKU barcode.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Right: Cart Panel ──────────────────────────── --}}
        <div class="flex w-full flex-col border-border bg-card md:w-[380px] lg:w-[420px]">

            {{-- Cart Header --}}
            <div class="flex items-center justify-between border-b border-border px-5 py-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-semibold text-foreground">Current Sale</h2>
                    @if ($cartItemCount > 0)
                        <span class="badge-primary">{{ $cartItemCount }}</span>
                    @endif
                </div>
                @if (!empty($cart))
                    <button wire:click="resetCart" class="btn-ghost text-xs text-muted-foreground h-8 px-2">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        Clear
                    </button>
                @endif
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto px-5 py-3">
                @if (empty($cart))
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-muted">
                            <svg class="h-7 w-7 text-muted-foreground/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        </div>
                        <p class="mt-3 text-sm text-muted-foreground">Cart is empty</p>
                        <p class="mt-1 text-xs text-muted-foreground/70">Tap a product to add it</p>
                    </div>
                @else
                    <div class="space-y-1" x-data="{ flashId: null }">
                        @foreach ($cart as $index => $item)
                            <div
                                class="group flex items-center gap-3 rounded-lg p-2.5 transition-colors hover:bg-muted/50 cart-item-enter"
                                x-data
                                x-init="$watch('$wire.cart', () => { if ({{ $index }} === $wire.cart.length - 1) { flashId = {{ $index }}; setTimeout(() => flashId = null, 400); } })"
                            >
                                {{-- Item Image Placeholder --}}
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-muted">
                                    <svg class="h-5 w-5 text-muted-foreground/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>

                                {{-- Item Details --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="truncate text-sm font-medium text-foreground">{{ $item['product_name'] }}</span>
                                        @if ($item['discount_percentage'] > 0)
                                            <span class="badge-warning shrink-0 text-[9px] px-1 py-0">-{{ $item['discount_percentage'] }}%</span>
                                        @endif
                                    </div>
                                    <div class="mt-0.5 flex items-center gap-1 text-xs text-muted-foreground">
                                        <span>{{ $item['size'] }}</span>
                                        <span>·</span>
                                        <span>{{ $item['color'] }}</span>
                                    </div>
                                </div>

                                {{-- Quantity Controls --}}
                                <div class="flex items-center gap-1">
                                    <button
                                        wire:click="updateQuantity({{ $item['variant_id'] }}, {{ $item['quantity'] - 1 }})"
                                        class="btn-icon-sm text-muted-foreground hover:text-foreground"
                                        @if ($item['quantity'] <= 1) disabled @endif
                                    >
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/></svg>
                                    </button>
                                    <span class="w-7 text-center text-sm font-semibold tabular-nums text-foreground">{{ $item['quantity'] }}</span>
                                    <button
                                        wire:click="updateQuantity({{ $item['variant_id'] }}, {{ $item['quantity'] + 1 }})"
                                        class="btn-icon-sm text-muted-foreground hover:text-foreground"
                                    >
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </button>
                                </div>

                                {{-- Subtotal + Remove --}}
                                <div class="flex flex-col items-end gap-0.5">
                                    <span class="text-sm font-semibold text-foreground tabular-nums">₱{{ number_format($item['subtotal'], 2) }}</span>
                                    <button
                                        wire:click="removeFromCart({{ $item['variant_id'] }})"
                                        class="text-muted-foreground/50 hover:text-destructive transition-colors"
                                        title="Remove"
                                    >
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Cart Footer --}}
            @if (!empty($cart))
                <div class="border-t border-border px-5 py-4 space-y-3">
                    {{-- Totals --}}
                    <div class="space-y-1.5">
                        @php
                            $hasDiscount = collect($cart)->contains(fn ($item) => $item['discount_percentage'] > 0);
                            $originalTotal = collect($cart)->sum(fn ($item) => $item['base_price'] * $item['quantity']);
                        @endphp
                        @if ($hasDiscount)
                            <div class="flex justify-between text-xs text-muted-foreground">
                                <span>Original</span>
                                <span class="line-through">₱{{ number_format($originalTotal, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-muted-foreground">Total</span>
                            <span class="text-xl font-bold text-foreground tabular-nums">₱{{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    <button
                        wire:click="openPaymentModal"
                        class="btn-primary-lg w-full"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        Pay ₱{{ number_format($cartTotal, 2) }}
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
                <h3 class="text-lg font-semibold text-foreground">Complete Sale</h3>
                <button wire:click="closePaymentModal" class="btn-icon text-muted-foreground">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="processSale" class="mt-6 space-y-5">
                {{-- Total --}}
                <div class="rounded-lg bg-muted/50 p-4 text-center">
                    <span class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Amount Due</span>
                    <div class="mt-1 text-3xl font-bold text-foreground tabular-nums">₱{{ number_format($cartTotal, 2) }}</div>
                </div>

                {{-- Payment Method --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-foreground">Payment Method</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            x-on:click="method = 'cash'"
                            class="flex items-center justify-center gap-2 rounded-lg border-2 p-3 text-sm font-medium transition-all"
                            :class="method === 'cash' ? 'border-primary bg-primary/5 text-primary' : 'border-border text-muted-foreground hover:border-border/80 hover:bg-muted/50'"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                            Cash
                        </button>
                        <button
                            type="button"
                            x-on:click="method = 'card'"
                            class="flex items-center justify-center gap-2 rounded-lg border-2 p-3 text-sm font-medium transition-all"
                            :class="method === 'card' ? 'border-primary bg-primary/5 text-primary' : 'border-border text-muted-foreground hover:border-border/80 hover:bg-muted/50'"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            Card
                        </button>
                    </div>
                </div>

                {{-- Cash Input --}}
                <div x-show="method === 'cash'" x-transition class="space-y-2">
                    <label for="cashReceived" class="text-sm font-medium text-foreground">Cash Received</label>
                    <input
                        type="number"
                        id="cashReceived"
                        step="0.01"
                        wire:model.live="cashReceived"
                        class="input-field-lg text-center text-lg font-semibold"
                        required
                    >
                    @error('cashReceived')
                        <p class="text-xs text-destructive">{{ $message }}</p>
                    @enderror
                    <div class="rounded-lg p-3 text-center {{ $change >= 0 ? 'bg-success/10' : 'bg-destructive/10' }}">
                        <span class="text-xs font-medium uppercase tracking-wider {{ $change >= 0 ? 'text-success' : 'text-destructive' }}">Change</span>
                        <div class="text-2xl font-bold tabular-nums {{ $change >= 0 ? 'text-success' : 'text-destructive' }}">
                            ₱{{ number_format($change, 2) }}
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
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Complete Sale
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- ── Variant Picker Dialog ───────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if ($showVariantPicker && $selectedProduct)
        <div class="dialog-overlay" wire:click="closeVariantPicker"></div>
        <div class="dialog-content max-w-md">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-foreground">{{ $selectedProduct->name }}</h3>
                    <p class="mt-0.5 text-sm text-muted-foreground">Select a variant to add to cart</p>
                </div>
                <button wire:click="closeVariantPicker" class="btn-icon text-muted-foreground">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            @php
                $pickerDiscount = $selectedProduct->activeDiscount();
                $pickerVariants = $selectedProduct->variants->filter(fn ($v) => $v->current_stock > 0);
            @endphp

            <div class="mt-5 grid grid-cols-2 gap-2 max-h-80 overflow-y-auto pr-1">
                @foreach ($pickerVariants as $variant)
                    @php
                        $pPrice = $variant->product->base_price * (1 - ($pickerDiscount?->percentage ?? 0) / 100);
                        $pLow = $variant->isLowStock();
                    @endphp
                    <button
                        wire:click="addSelectedVariantToCart({{ $variant->id }})"
                        class="{{ $pLow ? 'variant-btn-low-stock' : 'variant-btn' }} justify-between"
                    >
                        <div class="w-full space-y-0.5">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-foreground">{{ $variant->getEffectiveSize() }}</span>
                                @if ($pLow)
                                    <span class="badge-destructive text-[9px] px-1 py-0">Low</span>
                                @endif
                            </div>
                            <div class="text-xs text-muted-foreground">{{ $variant->color }}</div>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <span class="text-sm font-semibold {{ $pickerDiscount ? 'text-primary' : 'text-foreground' }}">
                                ₱{{ number_format($pPrice, 2) }}
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
    @endif
</div>
