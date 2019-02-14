@extends('layouts.app')

@section('title', 'Decompressor')

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
