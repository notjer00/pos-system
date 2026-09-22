<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-lg font-semibold" style="color: #e0dcd4;">{{ __('Profile') }}</h1>
            <p class="text-sm mt-0.5" style="color: #706860;">{{ __('Manage your account settings and preferences.') }}</p>
        </div>

        <div class="card p-5 sm:p-6 max-w-xl">
            <livewire:profile.update-profile-information-form />
        </div>

        <div class="card p-5 sm:p-6 max-w-xl">
            <livewire:profile.update-password-form />
        </div>

        <div class="card p-5 sm:p-6 max-w-xl">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
