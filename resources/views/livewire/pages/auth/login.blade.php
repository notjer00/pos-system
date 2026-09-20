<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        $redirectTo = $user->isAdmin() ? route('dashboard', absolute: false) : route('cashier.checkout', absolute: false);

        $this->redirect($redirectTo, navigate: true);
    }
}; ?>

<div class="min-h-screen w-full flex flex-col lg:flex-row overflow-x-hidden font-sans select-none sm:select-text"
     style="background: #0d0f10;">
    <!-- Left Hero Column (Branding & Logo) -->
    <div class="relative w-full lg:w-[46%] xl:w-[44%] min-h-[320px] lg:min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-12 text-white z-10 lg:overflow-visible animate-fade-in"
         style="background: #0a0c0e;">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none"></div>

        <!-- Top-left Small Logo Header -->
        <div class="relative z-10 flex items-center gap-3 animate-fade-in-up">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center" style="background: rgba(212,160,80,0.15); border: 1px solid rgba(212,160,80,0.2);">
                <img src="{{ asset('images/ELEVATION.jpg.jpg') }}" alt="Elevation Logo" class="h-7 w-7 sm:h-8 sm:w-8 object-contain" />
            </div>
        </div>

        <!-- Center Content: Main Brand Circular Emblem & Portal Title -->
        <div class="relative z-10 flex flex-col items-center justify-center my-auto py-8 sm:py-12 text-center">
            <div>
                <img
                    src="{{ asset('images/ELEVATION.jpg.jpg') }}"
                    alt="Elevation"
                    class="relative w-36 h-36 sm:w-48 sm:h-48 md:w-56 md:h-56 object-contain drop-shadow-xl transition-transform hover:scale-105 duration-200"
                />
            </div>

            <h1 class="mt-6 sm:mt-8 text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-wider uppercase drop-shadow-md animate-fade-in-up [animation-delay:0.15s]"
                style="color: #e0dcd4;">
                ELEVATION
            </h1>
            <p class="mt-2 text-sm sm:text-base lg:text-lg font-medium tracking-wide animate-fade-in-up [animation-delay:0.3s]"
               style="color: #d4a050;">
                POS Portal
            </p>
        </div>

        <!-- Bottom Spacer (Maintains vertical balance) -->
        <div class="relative z-10 hidden lg:block h-6"></div>

        <!-- Layered Organic Curve Divider (Visible on Desktop lg:) -->
        <div class="hidden lg:block absolute top-0 bottom-0 -right-20 xl:-right-28 w-20 xl:w-28 pointer-events-none z-20 overflow-visible">
            <svg class="h-full w-full" viewBox="0 0 100 1000" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,0 Q120,480 30,1000 L0,1000 Z" fill="#1a1510" opacity="0.5" />
                <path d="M0,0 Q105,480 15,1000 L0,1000 Z" fill="#0a0c0e" />
            </svg>
        </div>
    </div>

    <!-- Right Content Column (Sign In Form) -->
    <div class="flex-1 min-h-[calc(100vh-320px)] lg:min-h-screen flex flex-col justify-center items-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12 relative z-10"
         style="background: #0d0f10;">
        <div class="w-full max-w-[420px] mx-auto animate-fade-in-up [animation-delay:0.35s]">
            <!-- Form Title -->
            <h2 class="text-3xl font-extrabold tracking-tight mb-8 animate-fade-in-up [animation-delay:0.45s]"
                style="color: #e0dcd4;">
                Sign In
            </h2>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form wire:submit="login" class="space-y-4">
                <!-- Email Address Input -->
                <div class="animate-fade-in-up [animation-delay:0.5s]">
                    <input
                        wire:model="form.email"
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email"
                        class="input-field w-full"
                    />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs" style="color: #f0a0a0;" />
                </div>

                <!-- Password Input with Show/Hide Toggle -->
                <div x-data="{ show: false }" class="animate-fade-in-up [animation-delay:0.6s]">
                    <div class="relative">
                        <input
                            :type="show ? 'text' : 'password'"
                            wire:model="form.password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                            class="input-field w-full pr-11"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="login-icon-btn"
                            tabindex="-1"
                            title="Toggle password visibility"
                        >
                            <!-- Eye icon (Show) -->
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye slash icon (Hide) -->
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs" style="color: #f0a0a0;" />
                </div>

                <!-- Remember Me & Forgot Password Row -->
                <div class="flex items-center justify-between pt-1 animate-fade-in-up [animation-delay:0.7s]">
                    <label for="remember" class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
                        <span class="relative inline-flex items-center justify-center w-4 h-4 rounded border transition-all duration-200 shrink-0"
                              style="border-color: rgba(255,255,255,0.18); background: rgba(255,255,255,0.04);"
                              x-data="{ checked: false }"
                              x-init="checked = $refs.rememberInput?.checked || false"
                              :style="checked ? 'background: #c89b3c; border-color: #c89b3c;' : ''"
                        >
                            <input
                                wire:model="form.remember"
                                id="remember"
                                type="checkbox"
                                name="remember"
                                x-ref="rememberInput"
                                @change="checked = $el.checked"
                                class="sr-only"
                            />
                            <svg x-show="checked" x-cloak class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-xs sm:text-sm" style="color: #706860;">{{ __('Remember Me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="login-link"
                            wire:navigate
                        >
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div class="pt-2 animate-fade-in-up [animation-delay:0.8s]">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary w-full justify-center px-8 py-3 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                        <svg wire:loading wire:target="login" class="animate-spin -ms-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ __('Login') }}</span>
                    </button>
                </div>
            </form>

            <!-- Bottom Powered By Note -->
            <div class="mt-12 text-center animate-fade-in-up [animation-delay:1s]">
                <p class="text-xs italic font-medium tracking-wide" style="color: rgba(212,160,80,0.6);">
                    {{ __('Powered by Elevation POS') }}
                </p>
            </div>
        </div>
    </div>
</div>
