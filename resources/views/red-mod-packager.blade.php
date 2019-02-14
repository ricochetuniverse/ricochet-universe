@extends('layouts.app')

@section('title', 'RED Mod Packager')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ action('ToolsController@index') }}" class="btn btn-outline-primary mb-3">
                    « Return to tools list
                </a>

                <div id="red-mod-packager-root"></div>

                <noscript>
                    <div class="alert alert-danger m-0" role="alert">
                        Please enable JavaScript to use the RED mod packager tool.
                    </div>
                </noscript>
            </div>
        </div>
    </div>
@endsection
