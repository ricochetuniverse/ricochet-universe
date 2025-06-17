@extends('layouts.app', [
    'selected_navbar_item' => 'about',
])

@section('title', 'About')
@section('og:url', action('AboutController@index'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">About Ricochet Universe</div>

                    <div class="card-body">
                        <p>
                            This website is created and hosted by <a href="https://ngyikp.com">ngyikp</a>.
                            You can check out the
                            <a href="https://gitlab.com/ngyikp/ricochet-levels">open source code at GitLab</a>.
                        </p>

                        <p>Thanks to Reflexive Entertainment for creating the Ricochet game series.</p>

                        <p class="m-0">
                            Special thanks to the
                            <a href="{{ action('DiscordRedirectController@index') }}">Ricochet Players Discord community</a>
                            for keeping the community alive after the official website has vanished.
                        </p>
                    </div>
                </div>

                @if ($authors->count() > 0 && $roundSum > 0)
                    <div class="card">
                        <div class="card-header">Level set contributors</div>

                        <div class="card-body">
                            <p>
                                Thanks to ~{{ number_format($authors->count()) }} level creators
                                contributing {{ number_format($roundSum) }} levels to play:
                            </p>

                            <ul class="list-unstyled aboutPage__columns">
                                @foreach ($authors as $author)
                                    <li class="mb-2">
                                        <a href="{{ action('LevelController@index', ['author' => $author->author]) }}">{{ $author->author }}</a>
                                        <span class="text-nowrap">({{ number_format($author->rounds_sum) }} levels)</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
