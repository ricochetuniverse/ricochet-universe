@php

$as = $as ?? 'h1';

@endphp
<{{ $as }} class="card-header">{{ $slot }}</{{ $as }}>
