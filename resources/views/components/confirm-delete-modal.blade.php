@props([
    'method' => 'delete',
])

<div
    x-data="{
        open: false,
        itemId: null,
        itemTitle: '',
        itemMessage: '',
        method: '{{ $method }}',
        openConfirm(id, title, message) {
            this.itemId = id;
            if (title) this.itemTitle = title;
            if (message) this.itemMessage = message;
            this.open = true;
        },
        closeConfirm() {
            this.open = false;
            this.itemId = null;
        },
        confirmAction() {
            @this.call(this.method, this.itemId);
            this.closeConfirm();
        }
    }"
    x-on:confirm-delete.window="
        openConfirm(
            $event.detail.id,
            $event.detail.title,
            $event.detail.message
        )
    "
    x-on:keydown.escape.window="open && closeConfirm()"
    x-cloak
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50"
        style="background: rgba(0,0,0,0.6);"
        @click="closeConfirm()"
    ></div>

    {{-- Modal --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed left-1/2 top-1/2 z-50 -translate-x-1/2 -translate-y-1/2 w-full max-w-md rounded-xl p-6 shadow-xl"
        style="background: #14161a; border: 1px solid rgba(255,255,255,0.08);"
        @click.stop
        role="dialog"
        aria-modal="true"
    >
        {{-- Icon + Text --}}
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl"
                 style="background: rgba(180, 50, 50, 0.15); border: 1px solid rgba(180, 50, 50, 0.2);">
                <svg class="h-6 w-6" style="color: #f0a0a0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold" style="color: #e0dcd4;" x-text="itemTitle"></h3>
                <p class="text-sm mt-1.5" style="color: #706860;" x-text="itemMessage"></p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3 mt-6" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.25rem;">
            <button
                type="button"
                @click="closeConfirm()"
                class="btn-secondary"
            >
                Cancel
            </button>
            <button
                type="button"
                @click="confirmAction()"
                class="btn-destructive"
            >
                Delete
            </button>
        </div>
    </div>
</div>
