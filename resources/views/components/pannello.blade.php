{{-- Full-screen popover panel (Popover API). Open it with a button using popovertarget="{{ id }}". --}}
@props(['id', 'etichetta'])

<div id="{{ $id }}" popover aria-label="{{ $etichetta }}" {{ $attributes->class('pannello') }}>
    {{ $slot }}
</div>
