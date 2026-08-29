<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $otp = '';
    public string $userId = '';

    public function mount(): void
    {
        $this->userId = Session::get('pending_verification_user_id');

        if (! $this->userId) {
            $this->redirect(route('register', absolute: false), navigate: true);
        }
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'string', 'digits:6'],
        ]);

        $user = User::find($this->userId);

        if (! $user) {
            $this->addError('otp', 'Invalid verification session. Please register again.');
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirect(route('dashboard', absolute: false), navigate: true);
            return;
        }

        if (! $user->otp_code || ! $user->otp_expires_at) {
            $this->addError('otp', 'OTP has expired or was not generated. Please request a new one.');
            return;
        }

        if (now()->isAfter($user->otp_expires_at)) {
            $this->addError('otp', 'OTP has expired. Please request a new one.');
            return;
        }

        if (! Hash::check($this->otp, $user->otp_code)) {
            $this->addError('otp', 'Invalid OTP. Please try again.');
            return;
        }

        // OTP is valid - verify email
        $now = now();
        $updated = $user->update([
            'email_verified_at' => $now,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        $freshUser = $user->fresh();
        logger('OTP verified for user: ' . $user->id . ', update result: ' . ($updated ? 'true' : 'false') . ', now: ' . $now . ', fresh email_verified_at: ' . ($freshUser->email_verified_at ?? 'null') . ', fresh otp_code: ' . ($freshUser->otp_code ?? 'null'));

        Session::forget('pending_verification_user_id');

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function resendOtp(): void
    {
        $user = User::find($this->userId);

        if (! $user || $user->hasVerifiedEmail()) {
            $this->redirect(route('register', absolute: false), navigate: true);
            return;
        }

        // Generate new OTP
        $otpCode = (string) random_int(100000, 999999);
        $otpExpiresAt = now()->addMinutes(10);

        $user->update([
            'otp_code' => Hash::make($otpCode),
            'otp_expires_at' => $otpExpiresAt,
        ]);

        Mail::to($user->email)->send(new \App\Mail\OtpVerificationMail($otpCode, $user->name));

        Session::flash('status', 'otp-resent');
    }

    public function logout(): void
    {
        Session::forget('pending_verification_user_id');
        $this->redirect(route('login', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('We\'ve sent a 6-digit verification code to your email. Please enter it below to verify your account.') }}
    </div>

    @if (session('status') === 'otp-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A verification code has been sent to your email address.') }}
        </div>
    @endif

    @if (session('status') === 'otp-resent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification code has been sent to your email address.') }}
        </div>
    @endif

    <form wire:submit="verifyOtp">
        <!-- OTP Code -->
        <div>
            <x-input-label for="otp" :value="__('Verification Code')" />
            <x-text-input
                wire:model="otp"
                id="otp"
                class="block mt-1 w-full text-center text-2xl tracking-widest"
                type="text"
                name="otp"
                required
                autocomplete="one-time-code"
                maxlength="6"
                inputmode="numeric"
            />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>

            <div class="flex items-center gap-4">
                <button
                    wire:click="resendOtp"
                    type="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    {{ __('Resend Code') }}
                </button>

                <button
                    wire:click="logout"
                    type="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </form>
</div>