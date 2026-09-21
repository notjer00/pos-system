<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Name *</label>
        <input type="text" wire:model="name" class="input-field w-full">
        @error('name') <p class="mt-1 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Base Price *</label>
        <input type="number" step="0.01" wire:model="base_price" class="input-field w-full">
        @error('base_price') <p class="mt-1 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Category</label>
        <input type="text" wire:model="category" class="input-field w-full" list="category-list">
        <datalist id="category-list">
            @foreach ($this->availableCategories as $cat)
                <option value="{{ $cat }}">
            @endforeach
        </datalist>
    </div>
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Brand</label>
        <input type="text" wire:model="brand" class="input-field w-full">
    </div>
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Unit *</label>
        <x-select
            wire-model="unit"
            :value="$unit"
            :options="[
                ['label' => 'Piece', 'value' => 'Piece'],
                ['label' => 'Pack', 'value' => 'Pack'],
                ['label' => 'Box', 'value' => 'Box'],
                ['label' => 'Bottle', 'value' => 'Bottle'],
                ['label' => 'Can', 'value' => 'Can'],
                ['label' => 'Tub', 'value' => 'Tub'],
                ['label' => 'Sachet', 'value' => 'Sachet'],
                ['label' => 'Pair', 'value' => 'Pair'],
                ['label' => 'Kilogram', 'value' => 'Kilogram'],
                ['label' => 'Gram', 'value' => 'Gram'],
                ['label' => 'Liter', 'value' => 'Liter'],
                ['label' => 'Milliliter', 'value' => 'Milliliter'],
                ['label' => 'Other', 'value' => 'Other'],
            ]"
            placeholder="Select unit"
        />
    </div>
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Product Image</label>
        <div class="flex items-center gap-3">
            <label class="btn-secondary cursor-pointer text-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Choose File
                <input type="file" wire:model.live="image" class="hidden" accept="image/jpg,image/jpeg,image/png,image/webp">
            </label>
            @if ($image)
                <div class="relative">
                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-16 h-16 rounded-lg object-contain" style="border: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.03);">
                    <button type="button" wire:click="$set('image', null)" class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full flex items-center justify-center text-xs" style="background: #b43232; color: #fff;">&#215;</button>
                </div>
            @elseif ($editingProduct && $editingProduct->image_path && !$removeExistingImage)
                <div class="relative">
                    <img src="{{ asset('storage/' . $editingProduct->image_path) }}" alt="Current" class="w-16 h-16 rounded-lg object-contain" style="border: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.03);">
                    <button type="button" wire:click="$set('removeExistingImage', true)" class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full flex items-center justify-center text-xs" style="background: #b43232; color: #fff;">&#215;</button>
                </div>
            @endif
        </div>
        @error('image') <p class="mt-1 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-[13px] font-medium mb-1.5" style="color: #a09890;">Status</label>
        <div class="flex items-center gap-2.5">
            <button
                type="button"
                wire:click="$set('is_active', {{ $is_active ? 'false' : 'true' }})"
                class="toggle-switch {{ $is_active ? 'is-on' : '' }}"
            >
                <span class="toggle-switch-bg"></span>
                <span class="toggle-switch-knob"></span>
            </button>
            <span class="text-sm font-medium" @if ($is_active) style="color: #5cc980;" @else style="color: #706860;" @endif>
                {{ $is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>
