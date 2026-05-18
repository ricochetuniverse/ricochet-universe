@extends('layouts.app')

@section('title', 'Too many requests')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-card>
                    <x-card.header>Too many requests</x-card.header>

                    <x-card.body>
                        You are sending too many requests in a short timeframe, please slow down and try again in
                        a few seconds.
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
