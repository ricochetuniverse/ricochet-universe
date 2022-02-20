@extends('layouts.app')

@section('title', 'Page not found')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">Page not found</div>

                    <div class="card-body">
                        <p>Sorry, the page you are looking for could not be found.</p>

                        <a href="{{ action('HomeController@index') }}" class="btn btn-outline-primary">
                            Go to home page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
