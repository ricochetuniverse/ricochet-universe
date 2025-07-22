@extends('layouts.app')

@section('title', 'Too many requests')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">Too many requests</div>

                    <div class="card-body">
                        Please try again in a few seconds.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
