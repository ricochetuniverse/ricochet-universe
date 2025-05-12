@php

    $active = ($selected_navbar_item ?? '') === $key;

@endphp
<li class="nav-item">
    <a href="{{ $href }}"
       class="nav-link @if ($active)active @endif"
       title="{{ $title }}"
       data-bs-toggle="tooltip"
       @if ($active)aria-current="page" @endif>
        {{ $text }}
    </a>
</li>
