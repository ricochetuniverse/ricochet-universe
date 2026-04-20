@extends('layouts.app')

@section('title', 'Too many requests')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <h1 class="card-header">Too many requests</h1>

                    <div class="card-body">
                        You are sending too many requests in a short timeframe, please slow down and try again in
                        a few seconds.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
