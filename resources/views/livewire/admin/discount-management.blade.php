<div class="flex h-[calc(100vh-4rem)] flex-col overflow-hidden">

    {{-- ── Top Bar ────────────────────────────────────────── --}}
    <div class="flex items-center justify-between border-b border-border px-6 py-3">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            </div>
            <h1 class="text-lg font-semibold text-foreground">Discount Management</h1>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add Discount
        </button>
    </div>

    {{-- ── Main Content ───────────────────────────────────── --}}
    <div class="flex-1 overflow-y-auto p-6">

        {{-- Search --}}
        <div class="mb-6">
            <div class="relative w-full md:w-96">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search discounts..."
                    class="input-field pl-9"
                >
            </div>
        </div>

        @if ($discounts->count() > 0)
            {{-- ── Discount Table ───────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border bg-muted/50">
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Name</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Discount</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Scope</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Period</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Commission</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Status</th>
                                <th class="px-4 py-3 text-right font-medium text-muted-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($discounts as $discount)
                                @php
                                    $isStoreWide = is_null($discount->product_id);
                                    $isActive = $discount->isCurrentlyActive();
                                    $isScheduled = $discount->is_active && !$isActive;
                                    $isExpired = !$discount->is_active && $discount->ends_at && $discount->ends_at->isPast();
                                @endphp
                                <tr class="transition-colors hover:bg-muted/30">
                                    {{-- Name --}}
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-foreground">{{ $discount->name }}</span>
                                    </td>

                                    {{-- Percentage --}}
                                    <td class="px-4 py-3">
                                        <span class="text-lg font-bold tabular-nums text-primary">{{ $discount->percentage }}%</span>
                                    </td>

                                    {{-- Scope Badge --}}
                                    <td class="px-4 py-3">
                                        @if ($isStoreWide)
                                            <span class="badge-primary gap-1">
                                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                                Store-wide
                                            </span>
                                        @else
                                            <span class="badge-secondary gap-1">
                                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                                {{ $discount->product->name ?? 'Deleted product' }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Period --}}
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-muted-foreground">
                                            @if ($discount->starts_at || $discount->ends_at)
                                                <span>{{ $discount->starts_at?->format('M d') ?? 'Start' }}</span>
                                                <span class="mx-1">→</span>
                                                <span>{{ $discount->ends_at?->format('M d, Y') ?? 'Ongoing' }}</span>
                                            @else
                                                <span>Anytime</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Commission Impact --}}
                                    <td class="px-4 py-3">
                                        @php
                                            $cashiers = \App\Models\User::where('role', 'cashier')->where('commission_rate', '>', 0)->get();
                                        @endphp
                                        @if ($cashiers->isNotEmpty() && $isActive)
                                            @php
                                                $rate = $cashiers->first()->commission_rate;
                                                $samplePrice = $isStoreWide ? (\App\Models\Product::where('is_active', true)->first()?->base_price ?? 0) : ($discount->product->base_price ?? 0);
                                                $loss = round($samplePrice * ($discount->percentage / 100) * ($rate / 100), 2);
                                            @endphp
                                            <span class="badge-warning text-[10px] gap-1">
                                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                                -₱{{ number_format($loss, 2) }}/item
                                            </span>
                                        @else
                                            <span class="text-xs text-muted-foreground">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Status Toggle --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <button
                                                wire:click="toggleActive({{ $discount->id }})"
                                                class="toggle-switch {{ $discount->is_active ? 'is-on' : '' }}"
                                            >
                                                <span class="toggle-switch-bg"></span>
                                                <span class="toggle-switch-knob"></span>
                                            </button>
                                            <span class="text-xs font-medium {{ $isActive ? 'text-success' : ($isScheduled ? 'text-warning' : ($isExpired ? 'text-muted-foreground' : 'text-muted-foreground')) }}">
                                                @if ($isActive) Active
                                                @elseif ($isScheduled) Scheduled
                                                @elseif ($isExpired) Expired
                                                @else Inactive
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="edit({{ $discount->id }})" class="btn-icon-sm text-muted-foreground hover:text-foreground" title="Edit">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </button>
                                            <button
                                                wire:click="delete({{ $discount->id }})"
                                                class="btn-icon-sm text-muted-foreground hover:text-destructive"
                                                title="Delete"
                                                onclick="return confirm('Delete this discount? This cannot be undone.')"
                                            >
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($discounts->hasPages())
                    <div class="border-t border-border px-4 py-3">
                        {{ $discounts->links() }}
                    </div>
                @endif
            </div>
        @else
            {{-- ── Empty State ──────────────────────────────── --}}
            <div class="card flex flex-col items-center justify-center py-16 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                    <svg class="h-8 w-8 text-muted-foreground/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-foreground">No discounts yet</h3>
                <p class="mt-1 text-sm text-muted-foreground">Create a discount to offer promotions to customers.</p>
                <button wire:click="create" class="btn-primary mt-4">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Create Discount
                </button>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- ── Create / Edit Dialog ────────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if ($showModal)
        <div class="dialog-overlay" wire:click="closeModal"></div>
        <div class="dialog-content max-w-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-foreground">
                    {{ $editingDiscount ? 'Edit Discount' : 'Create Discount' }}
                </h3>
                <button wire:click="closeModal" class="btn-icon text-muted-foreground">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="mt-6 space-y-5">

                {{-- Name + Percentage --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="discount-name" class="text-sm font-medium text-foreground">Name *</label>
                        <input
                            type="text"
                            id="discount-name"
                            wire:model="name"
                            class="input-field"
                            placeholder="e.g. Summer Sale"
                            required
                        >
                        @error('name') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="discount-pct" class="text-sm font-medium text-foreground">Percentage *</label>
                        <div class="relative">
                            <input
                                type="number"
                                id="discount-pct"
                                step="0.01"
                                min="0"
                                max="100"
                                wire:model.live="percentage"
                                class="input-field pr-8"
                                placeholder="0"
                                required
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">%</span>
                        </div>
                        @error('percentage') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Scope: Product Selector --}}
                <div class="space-y-2">
                    <label for="discount-product" class="text-sm font-medium text-foreground">Scope</label>
                    <select
                        id="discount-product"
                        wire:model.live="product_id"
                        class="input-field"
                    >
                        <option value="">Store-wide: applies to all products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}: ₱{{ number_format($product->base_price, 2) }}</option>
                        @endforeach
                    </select>
                    @if (empty($product_id))
                        <div class="flex items-center gap-1.5 text-xs text-muted-foreground">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <span>Leave blank to apply store-wide. Select a product to limit this discount to one item.</span>
                        </div>
                    @endif
                </div>

                {{-- Active Toggle --}}
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        wire:click="$set('is_active', !{{ $is_active ? 'true' : 'false' }})"
                        class="toggle-switch {{ $is_active ? 'is-on' : '' }}"
                    >
                        <span class="toggle-switch-bg"></span>
                        <span class="toggle-switch-knob"></span>
                    </button>
                    <div>
                        <span class="text-sm font-medium text-foreground">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                        <span class="text-xs text-muted-foreground ml-1">{{ $is_active ? 'Discount is live' : 'Discount is paused' }}</span>
                    </div>
                </div>

                {{-- Date Range --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="discount-starts" class="text-sm font-medium text-foreground">Starts At</label>
                        <input
                            type="datetime-local"
                            id="discount-starts"
                            wire:model="starts_at"
                            class="input-field"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label for="discount-ends" class="text-sm font-medium text-foreground">Ends At</label>
                        <input
                            type="datetime-local"
                            id="discount-ends"
                            wire:model="ends_at"
                            class="input-field"
                        >
                    </div>
                </div>

                {{-- Commission Impact Preview --}}
                @if ($commissionPreview && count($commissionPreview) > 0)
                    <div class="rounded-lg border border-warning/30 bg-warning/5 p-4 space-y-3">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            <span class="text-sm font-semibold text-foreground">Commission Impact</span>
                            <span class="badge-warning text-[10px]">{{ $percentage }}% discount</span>
                        </div>
                        <div class="text-xs text-muted-foreground">
                            How this {{ empty($product_id) ? 'store-wide' : 'product' }} discount affects cashier commissions:
                        </div>
                        <div class="space-y-2">
                            @foreach ($commissionPreview as $preview)
                                <div class="flex items-center justify-between rounded-md bg-background px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-muted text-[10px] font-semibold text-muted-foreground">
                                            {{ strtoupper(substr($preview['cashier_name'], 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-foreground">{{ $preview['cashier_name'] }}</span>
                                        <span class="text-xs text-muted-foreground">({{ $preview['commission_rate'] }}%)</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm tabular-nums">
                                        <span class="text-muted-foreground">₱{{ number_format($preview['base_commission'], 2) }}</span>
                                        <svg class="h-3 w-3 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        <span class="font-semibold text-warning">₱{{ number_format($preview['discounted_commission'], 2) }}</span>
                                        <span class="text-xs text-destructive">-₱{{ number_format($preview['commission_loss'], 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex justify-end gap-3 border-t border-border pt-4">
                    <button type="button" wire:click="closeModal" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ $editingDiscount ? 'Update Discount' : 'Create Discount' }}
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
