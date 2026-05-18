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
                <a href="{{ action('ToolsController@index') }}" class="btn btn-outline-primary">
                    « Return to tools list
                </a>
            </div>
        </div>

        <noscript>
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger m-0" role="alert">
                        Please enable JavaScript to use the image to canvas tool.
                    </div>
                </div>
            </div>
        </noscript>

        <div class="row">
            <div class="col">
                <div id="image-to-canvas-root">
                    <div class="card">
                        <h1 class="card-header">Image to canvas</h1>

                        <div class="card-body">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
