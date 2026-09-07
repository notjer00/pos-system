<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 px-4">
    <div>
        <a href="/" wire:navigate>
            <x-application-logo class="w-20 h-20 drop-shadow" />
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-xl">
        {{ $slot }}
    </div>
</div>

