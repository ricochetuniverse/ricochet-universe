@extends('layouts.app')

@section('title', 'Under maintenance')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-card>
                    <x-card.header>Under maintenance</x-card.header>

                    <x-card.body>
                        Sorry, the website is currently under maintenance, please try again in a few minutes.
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
