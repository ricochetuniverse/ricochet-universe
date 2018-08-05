@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">Welcome</div>

                    <div class="card-body">
                        <p>Welcome to Ricochet Levels!</p>

                        <p class="m-0">
                            The home page is a bit empty for now,
                            <a href="{{ action('LevelController@index') }}">check out the levels catalog</a>
                            instead.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
