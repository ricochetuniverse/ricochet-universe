@extends('layouts.app')

@section('title', 'Page not found')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-card>
                    <x-card.header>Page not found</x-card.header>

                    <x-card.body>
                        <p>Sorry, the page you are looking for could not be found.</p>

                        <x-button href="{{ action('HomeController@index') }}">
                            Go to home page
                        </x-button>
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
