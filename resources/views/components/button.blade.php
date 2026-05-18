@php

$as = $as ?? (isset($attributes['href']) ? 'a' : 'button');
$appearance = $appearance ?? 'primary';
$active = $active ?? false;
$class = $class ?? '';

@endphp
<{{ $as }} class="btn btn-outline-{{ $appearance }} {{ $active ? 'active' : '' }} {{ $class }}" @if ($active)aria-pressed="true"@endif {{ $attributes->except(['as', 'appearance', 'class', 'active']) }}>{{ $slot }}</{{ $as }}>
