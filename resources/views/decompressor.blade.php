@extends('layouts.app', [
    'selected_navbar_item' => 'tools',
])

@section('title', 'Decompressor')
@section('og:url', action('DecompressorController@index'))
@section('description', 'Decompress Ricochet levels, stats and other data.')

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <a href="{{ action('ToolsController@index') }}" class="btn btn-outline-primary">
                    « Return to tools list
                </a>
            </div>
        </div>

        <noscript>
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger m-0" role="alert">
                        Please enable JavaScript to use the decompressor tool.
                    </div>
                </div>
            </div>
        </noscript>

        <div class="row">
            <div class="col">
                <div id="decompressor-root" data-dotnet-loader-url="{{ $dotnetLoaderUrl }}">
                    <div class="card">
                        <h1 class="card-header">Decompressor</h1>

                        <div class="card-body">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
