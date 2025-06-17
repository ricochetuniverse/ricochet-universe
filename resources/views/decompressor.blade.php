@extends('layouts.app', [
    'selected_navbar_item' => 'tools',
])

@section('title', 'Decompressor')
@section('og:url', action('DecompressorController@index'))
@section('description', 'Decompress Ricochet levels, stats and other data.')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ action('ToolsController@index') }}" class="btn btn-outline-primary mb-3">
                    « Return to tools list
                </a>

                <div id="decompressor-root"></div>

                <noscript>
                    <div class="alert alert-danger m-0" role="alert">
                        Please enable JavaScript to use the decompressor tool.
                    </div>
                </noscript>
            </div>
        </div>
    </div>
@endsection
