@php

$tag = $tag ?? 'h1';

@endphp
<{{ $tag }} class="card-header">{{ $slot }}</{{ $tag }}>
