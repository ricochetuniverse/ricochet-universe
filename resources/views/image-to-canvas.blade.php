@extends('layouts.app')

@section('title', 'Image to Canvas')
@section('description', '')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ action('ToolsController@index') }}" class="btn btn-outline-primary mb-3">
                    « Return to tools list
                </a>

                <div id="image-to-canvas-root"></div>

                <noscript>
                    <div class="alert alert-danger m-0" role="alert">
                        Please enable JavaScript to use the image to canvas tool.
                    </div>
                </noscript>
            </div>
        </div>
    </div>
@endsection
