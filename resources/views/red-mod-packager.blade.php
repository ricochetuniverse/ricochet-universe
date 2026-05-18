@extends('layouts.app', [
    'selected_navbar_item' => 'tools',
])

@section('title', 'RED Mod Packager')
@section('og:url', action('RedModPackagerController@index'))
@section('description', 'Package your Ricochet mods to a .RED file for easier distribution.')

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
                    <x-alert type="danger">
                        Please enable JavaScript to use the RED mod packager tool.
                    </x-alert>
                </div>
            </div>
        </noscript>

        <div class="row">
            <div class="col">
                <div id="red-mod-packager-root">
                    <div class="card">
                        <h1 class="card-header">RED mod packager</h1>

                        <div class="card-body">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
