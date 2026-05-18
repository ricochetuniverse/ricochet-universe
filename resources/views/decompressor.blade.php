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
                <x-button href="{{ action('ToolsController@index') }}">
                    « Return to tools list
                </x-button>
            </div>
        </div>

        <noscript>
            <div class="row">
                <div class="col">
                    <x-alert type="danger">
                        Please enable JavaScript to use the decompressor tool.
                    </x-alert>
                </div>
            </div>
        </noscript>

        <div class="row">
            <div class="col">
                <div id="decompressor-root" data-dotnet-loader-url="{{ $dotnetLoaderUrl }}">
                    <x-card>
                        <x-card.header>Decompressor</x-card.header>

                        <x-card.body>
                            Loading...
                        </x-card.body>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
@endsection
