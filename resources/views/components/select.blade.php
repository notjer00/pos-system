@props([
    'options' => [],
    'placeholder' => 'Select...',
    'wireModel' => null,
    'value' => null,
])

@php
    $safeOptions = json_encode($options);
    $safePlaceholder = e($placeholder);
    $safeWireModel = e($wireModel ?? '');
    $safeValue = e($value ?? '');
@endphp

<div
    x-data="{
        open: false,
        selected: '{{ $safeValue }}',
        focused: -1,
        options: {{ $safeOptions }},
        get selectedLabel() {
            const found = this.options.find(o => String(o.value) === String(this.selected));
            return found ? found.label : '{{ $safePlaceholder }}';
        },
        select(option) {
            this.selected = option.value;
            this.open = false;
            this.focused = -1;
            $wire.set('{{ $safeWireModel }}', option.value);
        },
        handleKeydown(e) {
            if (!this.open) {
                if (['ArrowDown','ArrowUp','Enter',' '].includes(e.key)) {
                    e.preventDefault();
                    this.open = true;
                    this.$nextTick(() => {
                        const idx = this.options.findIndex(o => String(o.value) === String(this.selected));
                        this.focused = idx >= 0 ? idx : 0;
                    });
                }
                return;
            }
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.focused = Math.min(this.focused + 1, this.options.length - 1);
                this.scrollFocused();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.focused = Math.max(this.focused - 1, 0);
                this.scrollFocused();
            } else if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (this.focused >= 0) this.select(this.options[this.focused]);
            } else if (e.key === 'Escape') {
                this.open = false;
                this.focused = -1;
            }
        },
        scrollFocused() {
            this.$nextTick(() => {
                const container = this.$refs.list;
                if (!container) return;
                const el = container.children[this.focused];
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        }
    }"
    @keydown="handleKeydown($event)"
    @click.away="open = false; focused = -1"
    class="relative"
>
    <button
        type="button"
        @click="open = !open"
        class="input-field flex items-center justify-between gap-2 text-left cursor-pointer select-none"
        :class="{ 'ring-2 ring-amber-500/30 border-amber-500/40': open }"
        style="{{ $attributes->get('style', '') }}"
    >
        <span x-text="selectedLabel" :class="{ 'text-[#706860]': !selected }" class="truncate"></span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" style="color: #5a5550;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.97]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.97]"
        class="absolute z-50 mt-1.5 w-full rounded-xl shadow-xl overflow-hidden"
        style="background: #14161a; border: 1px solid rgba(255,255,255,0.08);"
    >
        <div x-ref="list" class="max-h-60 overflow-y-auto py-1" role="listbox">
            <template x-for="(option, index) in options" :key="option.value">
                <button
                    type="button"
                    role="option"
                    @click="select(option)"
                    @mouseenter="focused = index"
                    class="w-full text-left px-3 py-2 text-sm transition-colors duration-100 flex items-center justify-between gap-2"
                    :class="{
                        'text-[#706860]': String(option.value) !== String(selected) && focused !== index,
                        'text-[#e0dcd4] bg-white/[0.06]': focused === index && String(option.value) !== String(selected),
                        'text-[#d4a850] bg-[#c89b3c]/15': String(option.value) === String(selected),
                        'text-[#d4a850] bg-[#c89b3c]/20': focused === index && String(option.value) === String(selected),
                    }"
                >
                    <span x-text="option.label" class="truncate"></span>
                    <svg x-show="String(option.value) === String(selected)" class="w-4 h-4 shrink-0" style="color: #d4a850;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </template>
        </div>
    </div>
</div>
