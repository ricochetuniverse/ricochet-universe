@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Welcome to Ricochet Universe</div>

                    <div class="card-body">
                        <p class="m-0">
                            Explore, download and play Ricochet Infinity and Ricochet Lost Worlds level sets created by the community.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md pr-md-0">
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

            <div class="col-md mt-3 mt-md-0 pr-xl-0">
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

            <div class="col-xl-auto mt-3 mt-xl-0">
                <div class="discordWidget__root">
                    <a href="https://discord.gg/{{ config('ricochet.discord_invite') }}" class="discordWidget__header">
                        <div class="discordWidget__logo">
                            <span class="sr-only">Discord</span>
                        </div>

                        <span class="btn btn-outline-secondary">Join</span>
                    </a>

                    <div class="discordWidget__reactWrap"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
