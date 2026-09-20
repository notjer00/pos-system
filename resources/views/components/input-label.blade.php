@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#a09890]']) }}>
    {{ $value ?? $slot }}
</label>
