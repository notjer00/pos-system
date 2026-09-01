<?php

namespace App\Livewire\Cashier;

use App\Models\CommissionLog;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleLineItem;
use App\Models\SalesTransaction;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $search = '';

    public $cart = [];

    public $showPaymentModal = false;

    public $paymentMethod = 'cash';

    public $cashReceived = 0;

    public $change = 0;

    public $skuScan = '';

    protected $listeners = ['refreshCart' => '$refresh'];

    public function mount(): void
    {
        $this->resetCart();
    }

    public function resetCart(): void
    {
        $this->cart = [];
        $this->showPaymentModal = false;
        $this->paymentMethod = 'cash';
        $this->cashReceived = 0;
        $this->change = 0;
        $this->skuScan = '';
    }

    public function addToCart(ProductVariant $variant): void
    {
        $activeDiscount = $variant->product->activeDiscount();
        $discountPercentage = $activeDiscount ? $activeDiscount->percentage : 0;
        $finalPrice = $variant->product->base_price * (1 - $discountPercentage / 100);

        $existingIndex = null;
        foreach ($this->cart as $index => $item) {
            if ($item['variant_id'] === $variant->id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $newQuantity = $this->cart[$existingIndex]['quantity'] + 1;
            if ($newQuantity > $variant->current_stock) {
                $this->dispatch('notify', message: 'Cannot add more. Stock limit reached.', type: 'error');

                return;
            }
            $this->cart[$existingIndex]['quantity'] = $newQuantity;
            $this->cart[$existingIndex]['subtotal'] = $newQuantity * $finalPrice;
        } else {
            if ($variant->current_stock <= 0) {
                $this->dispatch('notify', message: 'Out of stock.', type: 'error');

                return;
            }
            $this->cart[] = [
                'variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'size' => $variant->size,
                'color' => $variant->color,
                'base_price' => $variant->product->base_price,
                'discount_percentage' => $discountPercentage,
                'final_price' => $finalPrice,
                'quantity' => 1,
                'subtotal' => $finalPrice,
            ];
        }

        $this->dispatch('notify', message: 'Added to cart!');
    }

    public function scanSku(): void
    {
        $sku = trim($this->skuScan);

        if (empty($sku)) {
            return;
        }

        $variant = ProductVariant::where('sku', $sku)
            ->where('current_stock', '>', 0)
            ->first();

        if (! $variant) {
            $this->dispatch('notify', message: "No product found for SKU: {$sku}", type: 'error');
            $this->skuScan = '';

            return;
        }

        $this->addToCart($variant);
        $this->skuScan = '';
    }

    public function updateQuantity(int $variantId, int $quantity): void
    {
        $variant = ProductVariant::find($variantId);
        if (! $variant) {
            return;
        }

        foreach ($this->cart as $index => $item) {
            if ($item['variant_id'] === $variantId) {
                if ($quantity <= 0) {
                    unset($this->cart[$index]);
                    $this->cart = array_values($this->cart);
                } elseif ($quantity > $variant->current_stock) {
                    $this->dispatch('notify', message: "Cannot exceed available stock ({$variant->current_stock}).", type: 'error');
                } else {
                    $this->cart[$index]['quantity'] = $quantity;
                    $this->cart[$index]['subtotal'] = $quantity * $item['final_price'];
                }
                break;
            }
        }
    }

    public function removeFromCart(int $variantId): void
    {
        $this->cart = array_values(array_filter($this->cart, fn ($item) => $item['variant_id'] !== $variantId));
    }

    public function getCartTotal(): float
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getCartItemCount(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function openPaymentModal(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Cart is empty.', type: 'error');

            return;
        }
        $this->showPaymentModal = true;
        $this->cashReceived = ceil($this->getCartTotal());
        $this->calculateChange();
    }

    public function calculateChange(): void
    {
        $this->change = max(0, (float) $this->cashReceived - $this->getCartTotal());
    }

    public function updatedCashReceived(): void
    {
        $this->calculateChange();
    }

    public function processSale(): void
    {
        $this->validate([
            'paymentMethod' => 'required|in:cash,card',
            'cashReceived' => 'required_if:paymentMethod,cash|numeric|min:0',
        ]);

        if ($this->paymentMethod === 'cash' && $this->cashReceived < $this->getCartTotal()) {
            $this->dispatch('notify', message: 'Insufficient cash received.', type: 'error');

            return;
        }

        try {
            DB::transaction(function () {
                // Determine the discount to record on the transaction.
                // Check each product in cart for product-specific discount first.
                // If no product-specific discount found, check for store-wide discount.
                $activeDiscount = null;
                $storeWideDiscount = Discount::query()
                    ->where('is_active', true)
                    ->whereNull('product_id')
                    ->where(function ($query) {
                        $query->whereNull('starts_at')
                            ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('ends_at')
                            ->orWhere('ends_at', '>=', now());
                    })
                    ->first();

                foreach ($this->cart as $item) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant && $variant->product->activeDiscount()) {
                        $discount = $variant->product->activeDiscount();
                        // If it's a product-specific discount, use it
                        if ($discount->product_id !== null) {
                            $activeDiscount = $discount;
                            break;
                        }
                    }
                }

                // If no product-specific discount found, use store-wide if available
                if (! $activeDiscount && $storeWideDiscount) {
                    $activeDiscount = $storeWideDiscount;
                }

                $transaction = SalesTransaction::create([
                    'cashier_id' => Auth::id(),
                    'discount_id' => $activeDiscount?->id,
                    'total_amount' => $this->getCartTotal(),
                    'status' => 'completed',
                ]);

                foreach ($this->cart as $item) {
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);

                    if (! $variant || $variant->current_stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$item['product_name']} ({$item['size']}/{$item['color']})");
                    }

                    $variant->decrement('current_stock', $item['quantity']);

                    SaleLineItem::create([
                        'sales_transaction_id' => $transaction->id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $item['quantity'],
                        'unit_price_at_sale' => $item['final_price'],
                    ]);

                    // Record stock movement for sale
                    StockMovement::create([
                        'product_variant_id' => $variant->id,
                        'type' => 'sale',
                        'quantity_change' => -$item['quantity'],
                        'note' => "Sale from transaction #{$transaction->id}",
                        'created_by' => Auth::id(),
                    ]);

                    $cashier = Auth::user();
                    CommissionLog::create([
                        'sales_transaction_id' => $transaction->id,
                        'user_id' => $cashier->id,
                        'base_amount' => $item['base_price'],
                        'discount_applied' => $item['discount_percentage'],
                        'final_price' => $item['final_price'],
                        'commission_rate' => $cashier->commission_rate,
                        'commission_earned' => $item['final_price'] * ($cashier->commission_rate / 100) * $item['quantity'],
                    ]);
                }
            });

            $this->resetCart();
            $this->dispatch('notify', message: 'Sale completed successfully!');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->paymentMethod = 'cash';
        $this->cashReceived = 0;
        $this->change = 0;
    }

    public function getProductsProperty()
    {
        return Product::with(['variants' => function ($query) {
            $query->where('current_stock', '>', 0);
        }])
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.cashier.checkout', [
            'products' => $this->products,
            'cartItemCount' => $this->getCartItemCount(),
            'cartTotal' => $this->getCartTotal(),
        ]);
    }
}
