<div class="flex h-[calc(100vh-4rem)] flex-col overflow-hidden" x-data>

    {{-- ── Top Bar ────────────────────────────────────────── --}}
    <div class="flex items-center justify-between px-6 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
        <div>
            <h1 class="text-lg font-semibold" style="color: #e0dcd4;">Discounts</h1>
            <p class="text-xs mt-0.5" style="color: #706860;">Manage discounts and promotions.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Create Discount
        </button>
    </div>

    {{-- ── Main Content ───────────────────────────────────── --}}
    <div class="flex-1 overflow-y-auto p-6">

        {{-- Search --}}
        <div class="mb-4">
            <div class="relative w-full md:w-96">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color: #5a5550;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
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
                    <table class="min-w-full dm-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Discount</th>
                                <th>Scope</th>
                                <th>Period</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discounts as $discount)
                                @php
                                    $isStoreWide = is_null($discount->product_id);
                                    $isActive = $discount->isCurrentlyActive();
                                    $isScheduled = $discount->is_active && !$isActive;
                                    $isExpired = !$discount->is_active && $discount->ends_at && $discount->ends_at->isPast();
                                @endphp
                                <tr>
                                    {{-- Name --}}
                                    <td>
                                        <span class="font-medium" style="color: #e0dcd4;">{{ $discount->name }}</span>
                                    </td>

                                    {{-- Percentage --}}
                                    <td>
                                        <span class="text-lg font-bold tabular-nums" style="color: #d4a850;">{{ $discount->percentage }}%</span>
                                    </td>

                                    {{-- Scope Badge --}}
                                    <td>
                                        @if ($isStoreWide)
                                            <span class="badge-primary gap-1">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                                Store-wide
                                            </span>
                                        @else
                                            <span class="badge-secondary gap-1">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                                {{ $discount->product->name ?? 'Deleted product' }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Period --}}
                                    <td>
                                        <div class="text-xs" style="color: #706860;">
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
                                    <td>
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
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                                -&#8369;{{ number_format($loss, 2) }}/item
                                            </span>
                                        @else
                                            <span class="text-xs" style="color: #706860;">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Status Toggle --}}
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <button
                                                wire:click="toggleActive({{ $discount->id }})"
                                                class="toggle-switch {{ $discount->is_active ? 'is-on' : '' }}"
                                            >
                                                <span class="toggle-switch-bg"></span>
                                                <span class="toggle-switch-knob"></span>
                                            </button>
                                            <span class="text-xs font-medium"
                                                @if ($isActive) style="color: #5cc980;"
                                                @elseif ($isScheduled) style="color: #d4b060;"
                                                @else style="color: #706860;"
                                                @endif>
                                                @if ($isActive) Active
                                                @elseif ($isScheduled) Scheduled
                                                @elseif ($isExpired) Expired
                                                @else Inactive
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="edit({{ $discount->id }})" class="btn-ghost btn-icon-sm" title="Edit">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </button>
                                            <button
                                                @click.prevent="$dispatch('confirm-delete', { id: {{ $discount->id }}, title: 'Delete Discount?', message: 'Are you sure you want to delete this discount? This action cannot be undone.' })"
                                                class="btn-ghost btn-icon-sm"
                                                style="color: #f0a0a0;"
                                                title="Delete"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
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
                    <div class="px-4 py-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
                        {{ $discounts->links() }}
                    </div>
                @endif
            </div>
        @else
            {{-- ── Empty State ──────────────────────────────── --}}
            <div class="dm-empty card">
                <div class="dm-empty-icon">
                    <svg class="h-7 w-7" style="color: #5a5550;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <h3 class="text-sm font-medium" style="color: #e0dcd4;">No discounts yet</h3>
                <p class="text-xs mt-1" style="color: #706860;">Create a discount to offer promotions to customers.</p>
                <button wire:click="create" class="mt-4 btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
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
            <div class="flex items-center justify-between pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <h3 class="text-base font-semibold" style="color: #e0dcd4;">
                    {{ $editingDiscount ? 'Edit Discount' : 'Create Discount' }}
                </h3>
                <button wire:click="closeModal" class="btn-ghost btn-icon-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="pt-4 space-y-5">

                {{-- Name + Percentage --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="discount-name" class="block text-[13px] font-medium" style="color: #a09890;">Name *</label>
                        <input
                            type="text"
                            id="discount-name"
                            wire:model="name"
                            class="input-field"
                            placeholder="e.g. Summer Sale"
                            required
                        >
                        @error('name') <p class="text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="discount-pct" class="block text-[13px] font-medium" style="color: #a09890;">Percentage *</label>
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
                        @error('percentage') <p class="text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Scope: Product Selector --}}
                <div class="space-y-2">
                    <label for="discount-product" class="block text-[13px] font-medium" style="color: #a09890;">Scope</label>
                    <x-select
                        wire-model="product_id"
                        :value="$product_id"
                        :options="array_merge([['label' => 'Store-wide: applies to all products', 'value' => '']], array_map(fn($p) => ['label' => $p->name . ': &#8369;' . number_format($p->base_price, 2), 'value' => $p->id], $products))"
                        placeholder="Store-wide: applies to all products"
                    />
                    @if (empty($product_id))
                        <div class="flex items-center gap-1.5 text-xs" style="color: #706860;">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
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
                        <span class="text-sm font-medium" style="color: #e0dcd4;">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                        <span class="text-xs ml-1" style="color: #706860;">{{ $is_active ? 'Discount is live' : 'Discount is paused' }}</span>
                    </div>
                </div>

                {{-- Date Range --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="discount-starts" class="block text-[13px] font-medium" style="color: #a09890;">Starts At</label>
                        <input
                            type="datetime-local"
                            id="discount-starts"
                            wire:model="starts_at"
                            class="input-field"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label for="discount-ends" class="block text-[13px] font-medium" style="color: #a09890;">Ends At</label>
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
                    <div class="dm-commission-preview">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            <span class="text-sm font-semibold" style="color: #e0dcd4;">Commission Impact</span>
                            <span class="badge-warning text-[10px]">{{ $percentage }}% discount</span>
                        </div>
                        <div class="text-xs" style="color: #706860;">
                            How this {{ empty($product_id) ? 'store-wide' : 'product' }} discount affects cashier commissions:
                        </div>
                        <div class="space-y-2">
                            @foreach ($commissionPreview as $preview)
                                <div class="dm-commission-row">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-semibold" style="background: rgba(255,255,255,0.06); color: #a09890;">
                                            {{ strtoupper(substr($preview['cashier_name'], 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium" style="color: #e0dcd4;">{{ $preview['cashier_name'] }}</span>
                                        <span class="text-xs" style="color: #706860;">({{ $preview['commission_rate'] }}%)</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm tabular-nums">
                                        <span style="color: #807870;">&#8369;{{ number_format($preview['base_commission'], 2) }}</span>
                                        <svg class="h-3 w-3" style="color: #706860;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        <span class="font-semibold" style="color: #d4b060;">&#8369;{{ number_format($preview['discounted_commission'], 2) }}</span>
                                        <span class="text-xs" style="color: #f0a0a0;">-&#8369;{{ number_format($preview['commission_loss'], 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <button type="button" wire:click="closeModal" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ $editingDiscount ? 'Update Discount' : 'Create Discount' }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <x-confirm-delete-modal />
</div>
