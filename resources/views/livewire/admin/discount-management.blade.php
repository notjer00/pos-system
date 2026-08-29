<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Discount Management</h1>
        <button wire:click="create" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Discount
        </button>
    </div>

    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search discounts..."
            class="input-field w-full md:w-96"
        >
    </div>

    @if ($discounts->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scope</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($discounts as $discount)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $discount->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $discount->percentage }}%</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if ($discount->product_id)
                                    {{ $discount->product->name }}
                                @else
                                    <span class="text-green-600 font-medium">Store-wide</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if ($discount->starts_at || $discount->ends_at)
                                    {{ $discount->starts_at?->format('M d, Y') ?? 'Anytime' }} - {{ $discount->ends_at?->format('M d, Y') ?? 'Ongoing' }}
                                @else
                                    Anytime
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button
                                    wire:click="toggleActive({{ $discount->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                                        @if ($discount->isCurrentlyActive())
                                            bg-green-100 text-green-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                    @if ($discount->isCurrentlyActive()) Active @else Inactive @endif
                                </button>
                                @if ($discount->is_active && !$discount->isCurrentlyActive())
                                    <span class="ml-2 text-xs text-yellow-600">(Scheduled)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button wire:click="edit({{ $discount->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="delete({{ $discount->id }})" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this discount?')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $discounts->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No discounts found</h3>
            <p class="mt-1 text-sm text-gray-500">Create a discount to offer promotions.</p>
            <button wire:click="create" class="mt-4 btn-primary">Create Discount</button>
        </div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $editingDiscount ? 'Edit Discount' : 'Create Discount' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="p-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="name" class="input-field w-full" required>
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Percentage *</label>
                                <input type="number" step="0.01" min="0" max="100" wire:model="percentage" class="input-field w-full" required>
                                @error('percentage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product (optional - leave blank for store-wide)</label>
                                <select wire:model="product_id" class="input-field w-full">
                                    <option value="">Store-wide</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Starts At</label>
                                <input type="datetime-local" wire:model="starts_at" class="input-field w-full">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ends At</label>
                                <input type="datetime-local" wire:model="ends_at" class="input-field w-full">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 border-t pt-4">
                            <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary">
                                {{ $editingDiscount ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>