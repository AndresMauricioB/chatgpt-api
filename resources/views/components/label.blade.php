@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm txt-white']) }}>
    {{ $value ?? $slot }}
</label>
