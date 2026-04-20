@extends('layouts.app')

@section('title', 'Unauthorized')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <h1 class="card-header">Unauthorized</h1>

                    <div class="card-body">
                        <p>You don’t have permission to visit this page.</p>

                        <a href="{{ action('HomeController@index') }}" class="btn btn-outline-primary">
                            Go to home page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
