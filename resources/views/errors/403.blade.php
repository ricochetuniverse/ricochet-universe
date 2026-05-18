@extends('layouts.app')

@section('title', 'Unauthorized')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-card>
                    <x-card.header>Unauthorized</x-card.header>

                    <x-card.body>
                        <p>You don’t have permission to visit this page.</p>

                        <x-button href="{{ action('HomeController@index') }}">
                            Go to home page
                        </x-button>
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
