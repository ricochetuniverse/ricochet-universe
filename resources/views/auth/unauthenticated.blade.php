@extends('layouts.app')

@section('title', 'Not signed in')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <h1 class="card-header">Not signed in</h1>

                    <div class="card-body">
                        <p class="m-0">
                            This page requires authentication which is not currently available for everyone yet.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
