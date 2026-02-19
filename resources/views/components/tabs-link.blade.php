@props(['name', 'error' => false])

<li class="me-2">
    <a href="#" x-on:click.prevent="tab = '{{ $name }}'" :class="{
           'text-red-600 border-red-600': {{ $error ? 'true' : 'false' }} && tab !== '{{ $name }}',
           'text-blue-600 border-blue-600 active': tab === '{{ $name }}' && !{{ $error ? 'true' : 'false' }},
           'text-red-600 border-red-600 active': tab === '{{ $name }}' && {{ $error ? 'true' : 'false' }},
           'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== '{{ $name }}' && !{{ $error ? 'true' : 'false' }}
       }" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200
       {{ $error ? 'border-red-600 text-red-600' : '' }}" :aria-current="tab === '{{ $name }}' ? 'page' : undefined">

        {{ $slot }}

        @if ($error)
            <i class="fa-solid fa-circle-exclamation ms-2 animate-pulse"></i>
        @endif
    </a>
</li>