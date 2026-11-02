@extends('layouts.app', [
    'selected_navbar_item' => 'tools',
])

@section('title', implode(' - ', array_filter([$path, $categoryName, 'Data browser'])))
@section('og:url', action('DataBrowserController@show', ['category' => $category, 'path' => $path]))
@section('description', 'View '.$path.' inside '.$categoryName.'.')

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
                        Please enable JavaScript to browse data files.
                    </x-alert>
                </div>
            </div>
        </noscript>

        <div class="row">
            <div class="col">
                <div id="data-browser-root" data-url-prefix="/data-browser/{{ $category }}">
                    <div class="vstack gap-3">
                        <x-card>
                            <x-card.header>Data browser</x-card.header>

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
