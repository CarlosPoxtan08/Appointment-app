@props(['name'])

<div x-show="Tab === '{{ $name }}'" style="display: none;">
    {{ $slot }}
</div>