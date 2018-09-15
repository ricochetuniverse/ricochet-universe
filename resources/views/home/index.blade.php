@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Welcome to Ricochet Levels</div>

                    <div class="card-body">
                        <p class="m-0">
                            Here you can download and play levels for Ricochet Lost Worlds and Ricochet Infinity.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Most downloaded level sets</div>

                    <ul class="list-group list-group-flush">
                        @foreach ($topLevelSets as $levelSet)
                            @include('home._compact_level')
                        @endforeach
                    </ul>
                </div>

                <div class="mt-3 text-right">
                    <a href="{{ action('LevelController@index') }}" class="btn btn-outline-primary">View more</a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Recent level sets</div>

                    <ul class="list-group list-group-flush">
                        @foreach ($recentLevelSets as $levelSet)
                            @include('home._compact_level')
                        @endforeach
                    </ul>
                </div>

                <div class="mt-3 text-right">
                    <a href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => 'DESC']) }}"
                       class="btn btn-outline-primary">View more</a>
                </div>
            </div>
        </div>
    </div>
@endsection
