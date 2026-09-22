<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $this->dispatch('notify', message: __('User accounts cannot be deleted from this page. Please contact an administrator.'), type: 'error');
    }
}; ?>

<section class="space-y-5">
    <header>
        <h2 class="text-base font-semibold" style="color: #e0dcd4;">
            {{ __('Account Deletion') }}
        </h2>

        <p class="text-xs" style="color: #706860;">
            {{ __('User accounts cannot be deleted from Profile Settings. Please contact an administrator if you need your account removed.') }}
        </p>
    </header>

    <div class="rounded-lg px-4 py-3 flex items-center gap-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
        <svg class="w-4 h-4 shrink-0" style="color: #706860;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
        <p class="text-xs" style="color: #a09890;">{{ __('Contact an administrator to request account removal.') }}</p>
    </div>
</section>
