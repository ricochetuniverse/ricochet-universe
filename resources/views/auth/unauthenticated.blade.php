@extends('layouts.app')

@section('title', 'Not signed in')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-card>
                    <x-card.header>Not signed in</x-card.header>

                    <x-card.body>
                        <p class="m-0">
                            This page requires authentication which is not currently available for everyone yet.
                        </p>
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
