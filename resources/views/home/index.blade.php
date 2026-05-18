@extends('layouts.app')

@section('og:url', action('HomeController@index'))

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <div class="card">
                    <h1 class="card-header">Welcome to Ricochet Universe</h1>

                    <div class="card-body">
                        <p class="m-0">
                            Explore, download and play Ricochet Infinity and Ricochet Lost Worlds level sets created by the community.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-gap-3">
            <div class="col-md pe-md-0 d-flex flex-column gap-3">
                <div class="card">
                    <h1 class="card-header">Most downloaded level sets</h1>

                    <ul class="list-group list-group-flush">
                        @foreach ($topLevelSets as $levelSet)
                            @include('home._compact_level')
                        @endforeach
                    </ul>
                </div>

                <div class="text-end">
                    <a href="{{ action('LevelController@index') }}" class="btn btn-outline-primary">View more</a>
                </div>
            </div>

            <div class="col-md pe-xl-0 d-flex flex-column gap-3">
                <div class="card">
                    <h1 class="card-header">Recent level sets</h1>

                    <ul class="list-group list-group-flush">
                        @foreach ($recentLevelSets as $levelSet)
                            @include('home._compact_level')
                        @endforeach
                    </ul>
                </div>

                <div class="text-end">
                    <a href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => 'DESC']) }}"
                       class="btn btn-outline-primary">View more</a>
                </div>
            </div>

            <div class="col-xl-auto">
                <div class="discordWidget__root">
                    <a href="{{ action('DiscordRedirectController@index') }}" class="discordWidget__header">
                        <div class="discordWidget__logo">
                            <span class="visually-hidden">Discord</span>
                        </div>

                        <span class="btn btn-outline-secondary">Join</span>
                    </a>

                    <div class="discordWidget__reactWrap"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
