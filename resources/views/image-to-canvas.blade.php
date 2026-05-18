@extends('layouts.app', [
    'selected_navbar_item' => 'tools',
])

@section('title', 'Image to Canvas')
@section('og:url', action('ImageToCanvasController@index'))
@section('description', '')

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
                        Please enable JavaScript to use the image to canvas tool.
                    </x-alert>
                </div>
            </div>
        </noscript>

        <div class="row">
            <div class="col">
                <div id="image-to-canvas-root">
                    <x-card>
                        <x-card.header>Image to canvas</x-card.header>

                        <x-card.body>
                            Loading...
                        </x-card.body>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
@endsection
