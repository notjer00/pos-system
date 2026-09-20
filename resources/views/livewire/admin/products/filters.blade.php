<div class="mb-6 flex flex-wrap gap-3">
    <input
        type="text"
        wire:model.live.debounce.300ms="search"
        placeholder="Search products..."
        class="input-field w-full md:w-64"
    >
    <x-select
        wire-model="filterCategory"
        :value="$filterCategory"
        :options="$this->categoryFilterOptions"
        placeholder="All Categories"
        class="w-full md:w-44"
    />
    <x-select
        wire-model="filterStatus"
        :value="$filterStatus"
        :options="[
            ['label' => 'All Status', 'value' => ''],
            ['label' => 'Active', 'value' => 'active'],
            ['label' => 'Inactive', 'value' => 'inactive'],
        ]"
        placeholder="All Status"
        class="w-full md:w-36"
    />
</div>
