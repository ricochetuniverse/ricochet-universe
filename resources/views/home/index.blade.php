@extends('layouts.app')

@section('og:url', action('HomeController@index'))

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <x-card>
                    <x-card.header>Welcome to Ricochet Universe</x-card.header>

                    <x-card.body>
                        <p class="m-0">
                            Explore, download and play Ricochet Infinity and Ricochet Lost Worlds level sets created by
                            the community.
                        </p>
                    </x-card.body>
                </x-card>
            </div>
        </div>

        <div class="row row-gap-3">
            <div class="col-md pe-md-0 d-flex flex-column gap-3">
                <x-card>
                    <x-card.header as="h2">Most downloaded level sets</x-card.header>

                    <ul class="list-group list-group-flush">
                        @foreach ($topLevelSets as $levelSet)
                            @include('home._compact_level')
                        @endforeach
                    </ul>
                </x-card>

                <div class="text-end">
                    <x-button href="{{ action('LevelController@index') }}">View more</x-button>
                </div>
            </div>

            <div class="col-md pe-xl-0 d-flex flex-column gap-3">
                <x-card>
                    <x-card.header as="h2">Recent level sets</x-card.header>

                    <ul class="list-group list-group-flush">
                        @foreach ($recentLevelSets as $levelSet)
                            @include('home._compact_level')
                        @endforeach
                    </ul>
                </x-card>

                <div class="text-end">
                    <x-button
                        href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => 'DESC']) }}">
                        View more
                    </x-button>
                </div>
            </div>

            <div class="col-xl-auto">
                <div class="discordWidget__root">
                    <a href="{{ action('DiscordRedirectController@index') }}" class="discordWidget__header">
                        <div class="discordWidget__logo">
                            <span class="visually-hidden">Discord</span>
                        </div>

                        <x-button appearance="secondary">Join</x-button>
                    </a>

                    <div class="discordWidget__reactWrap"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
