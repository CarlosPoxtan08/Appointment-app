@props(['name'])

<div x-show="tab === '{{ $name }}'" style="display: none;">
    {{ $slot }}
</div>