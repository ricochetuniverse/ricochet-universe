@extends('layouts.app', [
    'selected_navbar_item' => 'tools',
])

@section('title', 'Data browser')
@section('og:url', action('DataBrowserController@index'))
@section('description', 'Browse the contents of a Ricochet Data.dat file.')

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <x-button href="{{ action('ToolsController@index') }}">
                    « Return to tools list
                </x-button>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-card>
                    <x-card.header>Data browser</x-card.header>

                    <x-card.body>
                        <p>Choose a data file:</p>

                        <ul class="m-0">
                            @foreach (config('ricochet.data_browser_categories') as $category => $name)
                                <li>
                                    <a href="{{ action('DataBrowserController@show', ['category' => $category]) }}">{{ $name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
