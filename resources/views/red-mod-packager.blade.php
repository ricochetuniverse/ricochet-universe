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
                <x-button href="{{ action('ToolsController@index') }}">
                    « Return to tools list
                </x-button>
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
                    <div class="vstack gap-3">
                        <x-card>
                            <x-card.header>RED mod packager</x-card.header>

                            <x-card.body>
                                Loading...
                            </x-card.body>
                        </x-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
