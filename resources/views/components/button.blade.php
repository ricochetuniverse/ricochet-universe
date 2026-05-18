@php

$as = $as ?? (isset($attributes['href']) ? 'a' : 'button');
$appearance = $appearance ?? 'primary';
$active = $active ?? false;
$class = $class ?? '';
$type = $type ?? ($as === 'button' ? 'button' : '');

@endphp
<{{ $as }} class="btn btn-outline-{{ $appearance }} {{ $active ? 'active' : '' }} {{ $class }}" @if ($active)aria-pressed="true"@endif @if ($type)type="{{ $type }}"@endif {{ $attributes->except(['as', 'appearance', 'active', 'class', 'type']) }}>{{ $slot }}</{{ $as }}>
