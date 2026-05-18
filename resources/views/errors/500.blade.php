@extends('layouts.app')

@section('title', 'Whoops')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-card>
                    <x-card.header>Whoops</x-card.header>

                    <x-card.body>
                        There was a problem processing this page, please report this issue to the site administrator.
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
