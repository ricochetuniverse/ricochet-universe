@php

    $active = ($selected_navbar_item ?? '') === $key;

@endphp
<li class="nav-item">
    <a href="{{ $href }}"
       class="nav-link js-with-tooltip @if ($active)active @endif"
       title="{{ $title }}"
       @if ($active)aria-current="page" @endif>
        {{ $text }}
    </a>
</li>
