<div class="p-4 md:p-6 h-screen flex flex-col overflow-hidden">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-900">Checkout</h1>
        <div class="text-sm text-gray-500">
            Cashier: {{ Auth::user()->name }}
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <div class="w-full md:w-2/3 lg:w-3/5 pr-4 overflow-y-auto">
            <div class="mb-4 flex gap-2">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search products..."
                    class="input-field w-full"
                >
                <input
                    type="text"
                    wire:model="skuScan"
                    wire:keydown.enter="scanSku"
                    placeholder="Scan SKU / Barcode..."
                    class="input-field w-64 bg-yellow-50 border-yellow-300"
                    autocomplete="off"
                    autofocus
                >
            </div>

            @if ($products->count() > 0)
                @foreach ($products as $product)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ $product->name }} @if ($product->activeDiscount()) <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded ml-2">{{ $product->activeDiscount()->percentage }}% OFF</span> @endif</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach ($product->variants as $variant)
                                @if ($variant->current_stock > 0)
                                    <button
                                        wire:click="addToCart({{ $variant->id }})"
                                        class="variant-btn p-3 text-left border rounded-lg hover:bg-gray-50 transition-colors
                                            @if ($variant->isLowStock())
                                                border-red-300 bg-red-50
                                            @else
                                                border-gray-200
                                            @endif
                                        "
                                    >
                                        <div class="font-medium text-sm">{{ $variant->size }} / {{ $variant->color }}</div>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-sm
                                                @if ($product->activeDiscount())
                                                    text-indigo-600
                                                @else
                                                    text-gray-900
                                                @endif
                                            ">
                                                @if ($product->activeDiscount())
                                                    ₱{{ number_format($variant->product->base_price * (1 - $product->activeDiscount()->percentage / 100), 2) }}
                                                    <span class="text-xs text-gray-400 line-through ml-1">₱{{ number_format($variant->product->base_price, 2) }}</span>
                                                @else
                                                    ₱{{ number_format($variant->product->base_price, 2) }}
                                                @endif
                                            </span>
                                            <span class="text-xs
                                                @if ($variant->isLowStock())
                                                    text-red-600 font-medium
                                                @else
                                                    text-gray-500
                                                @endif
                                            ">
                                                Stock: {{ $variant->current_stock }}
                                            </span>
                                        </div>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products available</h3>
                    <p class="mt-1 text-sm text-gray-500">All products are out of stock or inactive.</p>
                </div>
            @endif
        </div>

        <div class="w-full md:w-1/3 lg:w-2/5 border-l border-gray-200 pl-4 flex flex-col">
            <div class="bg-white rounded-lg shadow-sm border p-4 flex-1 flex flex-col">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cart ({{ $cartItemCount }} items)</h3>

                @if (empty($cart))
                    <div class="flex-1 flex items-center justify-center text-gray-500">
                        <p>Cart is empty</p>
                    </div>
                @else
                    <div class="flex-1 overflow-y-auto mb-4">
                        @foreach ($cart as $item)
                            <div class="flex gap-3 mb-3 pb-3 border-b last:border-0">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm">{{ $item['product_name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $item['size'] }} / {{ $item['color'] }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if ($item['discount_percentage'] > 0)
                                            ₱{{ number_format($item['base_price'], 2) }} <span class="line-through text-gray-400">→ ₱{{ number_format($item['final_price'], 2) }} ({{ $item['discount_percentage'] }}% off)</span>
                                        @else
                                            ₱{{ number_format($item['final_price'], 2) }} each
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button wire:click="updateQuantity({{ $item['variant_id'] }}, {{ $item['quantity'] - 1 }})" class="btn-icon text-gray-500 hover:text-gray-700" @if ($item['quantity'] <= 1) disabled @endif>-</button>
                                    <span class="w-10 text-center text-sm font-medium">{{ $item['quantity'] }}</span>
                                    <button wire:click="updateQuantity({{ $item['variant_id'] }}, {{ $item['quantity'] + 1 }})" class="btn-icon text-gray-500 hover:text-gray-700">+</button>
                                    <button wire:click="removeFromCart({{ $item['variant_id'] }})" class="btn-icon text-red-500 hover:text-red-700 ml-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">₱{{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span>₱{{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($cart))
                    <button wire:click="openPaymentModal" class="btn-primary w-full mt-4 py-3 text-lg">
                        Checkout
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePaymentModal"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Payment</h3>
                        <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="processSale" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total: <span class="text-xl font-bold text-gray-900">₱{{ number_format($cartTotal, 2) }}</span></label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model="paymentMethod" value="cash" class="h-4 w-4 text-indigo-600 border-gray-300">
                                    <span class="ml-2">Cash</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model="paymentMethod" value="card" class="h-4 w-4 text-indigo-600 border-gray-300">
                                    <span class="ml-2">Card</span>
                                </label>
                            </div>
                        </div>

                        @if ($paymentMethod === 'cash')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cash Received</label>
                                <input type="number" step="0.01" wire:model.live="cashReceived" class="input-field w-full text-lg" required>
                                <p class="mt-1 text-sm {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Change: ₱{{ number_format($change, 2) }}
                                </p>
                                @error('cashReceived') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div class="flex justify-end space-x-3 border-t pt-4">
                            <button type="button" wire:click="closePaymentModal" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary" :disabled="paymentMethod === 'cash' && cashReceived < cartTotal">
                                Complete Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>