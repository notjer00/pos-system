<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public string $role = 'cashier';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen w-full flex flex-col lg:flex-row bg-white overflow-x-hidden font-sans select-none sm:select-text">
    <!-- Left Hero Column (Branding & Logo) -->
    <div class="relative w-full lg:w-[46%] xl:w-[44%] min-h-[320px] lg:min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-12 bg-gradient-to-b from-[#115243] via-[#0b3b30] to-[#041c17] text-white z-10 lg:overflow-visible animate-fade-in">
        <!-- Background Glow & Giant Watermark Container -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Radial glow effect -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-teal-500/10 rounded-full blur-2xl animate-float-slower"></div>

            <!-- Giant background seal watermark -->
            <div class="absolute -bottom-20 -left-24 w-[460px] h-[460px] lg:w-[540px] lg:h-[540px] opacity-[0.08] select-none pointer-events-none animate-spin-slower">
                <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain filter brightness-200" alt="" />
            </div>
        </div>

        <!-- Top-left Small Logo Header -->
        <div class="relative z-10 flex items-center gap-3 animate-fade-in-up">
            <img src="{{ asset('images/logo.png') }}" alt="Tribu Pakaras Logo" class="h-10 w-10 sm:h-12 sm:w-12 rounded-full shadow-lg border border-white/20 object-contain" />
        </div>

        <!-- Center Content: Main Brand Circular Emblem & Portal Title -->
        <div class="relative z-10 flex flex-col items-center justify-center my-auto py-8 sm:py-12 text-center">
            <div class="relative">
                <div class="absolute -inset-2 rounded-full bg-emerald-400/20 blur-xl animate-glow-pulse"></div>
                <div class="animate-logo-float">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Tribu Pakaras"
                        class="relative w-36 h-36 sm:w-48 sm:h-48 md:w-56 md:h-56 object-contain drop-shadow-2xl rounded-full transition-transform hover:scale-105 duration-300"
                    />
                </div>
            </div>

            <h1 class="mt-6 sm:mt-8 text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-wider text-white uppercase drop-shadow-md animate-fade-in-up [animation-delay:0.15s]">
                TRIBU PAKARAS
            </h1>
            <p class="mt-2 text-sm sm:text-base lg:text-lg text-emerald-100/90 font-medium tracking-wide animate-fade-in-up [animation-delay:0.3s]">
                ASIS - POS Portal
            </p>
        </div>

        <!-- Bottom Spacer (Maintains vertical balance) -->
        <div class="relative z-10 hidden lg:block h-6"></div>

        <!-- Layered Organic Curve Divider (Visible on Desktop lg:) -->
        <div class="hidden lg:block absolute top-0 bottom-0 -right-20 xl:-right-28 w-20 xl:w-28 pointer-events-none z-20 overflow-visible">
            <svg class="h-full w-full" viewBox="0 0 100 1000" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="leftHeroGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#115243" />
                        <stop offset="50%" stop-color="#0b3b30" />
                        <stop offset="100%" stop-color="#041c17" />
                    </linearGradient>
                </defs>
                <!-- Subtle layered background curve -->
                <path d="M0,0 Q120,480 30,1000 L0,1000 Z" fill="#2d8570" opacity="0.3" />
                <!-- Primary matching gradient curve -->
                <path d="M0,0 Q105,480 15,1000 L0,1000 Z" fill="url(#leftHeroGradient)" />
            </svg>
        </div>
    </div>

    <!-- Right Content Column (Sign In Form) -->
    <div class="flex-1 min-h-[calc(100vh-320px)] lg:min-h-screen flex flex-col justify-center items-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12 bg-white relative z-10">
        <div class="w-full max-w-[420px] mx-auto animate-fade-in-up [animation-delay:0.35s]">
            <!-- Form Title -->
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-8 animate-fade-in-up [animation-delay:0.45s]">
                Sign In
            </h2>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form wire:submit="login" class="space-y-4">
                <!-- Role Selector (Student / Staff dropdown style from reference) -->
                <div class="animate-fade-in-up [animation-delay:0.5s]">
                    <div class="relative">
                        <select
                            wire:model="role"
                            id="role"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-[#0e5243] focus:border-[#0e5243] appearance-none cursor-pointer pr-10 shadow-sm transition"
                        >
                            <option value="cashier">Cashier</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-400">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Email Address Input -->
                <div class="animate-fade-in-up [animation-delay:0.6s]">
                    <input
                        wire:model="form.email"
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#0e5243] focus:border-[#0e5243] shadow-sm transition"
                    />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs text-red-600" />
                </div>

                <!-- Password Input with Show/Hide Toggle -->
                <div x-data="{ show: false }" class="animate-fade-in-up [animation-delay:0.7s]">
                    <div class="relative">
                        <input
                            :type="show ? 'text' : 'password'"
                            wire:model="form.password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#0e5243] focus:border-[#0e5243] shadow-sm pr-11 transition"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-400 hover:text-gray-600 focus:outline-none transition cursor-pointer"
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
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs text-red-600" />
                </div>

                <!-- Remember Me & Forgot Password Row -->
                <div class="flex items-center justify-between pt-1 animate-fade-in-up [animation-delay:0.8s]">
                    <label for="remember" class="inline-flex items-center cursor-pointer select-none">
                        <input
                            wire:model="form.remember"
                            id="remember"
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-[#0e5243] focus:ring-[#0e5243] shadow-sm cursor-pointer"
                        />
                        <span class="ms-2 text-xs sm:text-sm text-gray-600">{{ __('Remember Me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-xs sm:text-sm text-gray-500 hover:text-[#0e5243] transition font-normal"
                            wire:navigate
                        >
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button (Prominent rounded button with brand green) -->
                <div class="pt-2 animate-fade-in-up [animation-delay:0.9s]">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-8 py-2.5 bg-[#0e5243] hover:bg-[#0a3f33] active:bg-[#072e25] text-white font-medium text-sm rounded-lg shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5243] transition duration-150 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                        <svg wire:loading wire:target="login" class="animate-spin -ms-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ __('Login') }}</span>
                    </button>
                </div>
            </form>

            <!-- Bottom Powered By Note (Matching reference footnote) -->
            <div class="mt-12 text-center animate-fade-in-up [animation-delay:1s]">
                <p class="text-xs italic text-[#0e5243]/80 font-medium tracking-wide">
                    {{ __('Powered by Tribu Pakaras POS') }}
                </p>
            </div>
        </div>
    </div>
</div>

