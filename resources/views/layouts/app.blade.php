<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width">

    <link href="{{ mix('app.scss') }}" rel="stylesheet">

    @hasSection('og:title')<meta property="og:title" content="@yield('og:title')">@endif
    {{--<meta property="og:type" content="website">--}}
    @hasSection('og:url')<meta property="og:url" content="@yield('og:url')">@endif
    <meta property="og:description" content="@yield('og:description', 'Explore and download Ricochet level sets created by the community')">
    <meta name="description" content="@yield('og:description', 'Explore and download Ricochet level sets created by the community')">
    @hasSection('og:image')
        <meta property="og:image" content="@yield('og:image')">
        <meta name="thumbnail" content="@yield('og:image')">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">

    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a class="navbar-brand d-flex mr-2" href="{{ action('HomeController@index') }}" title="Ricochet" data-toggle="tooltip">
                    <img src="{{ asset('images/ricochet-logo.png') }}" width="28" height="28" alt="Ricochet">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('LevelController@index') }}" title="Explore and download level sets created by the community" data-toggle="tooltip">Levels</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ action('UploadController@index') }}" title="Upload and share your level sets" data-toggle="tooltip">Upload</a>
                            </li>
                        @endauth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('ReviverController@index') }}" title="Restore the in-game level catalog in Ricochet Infinity" data-toggle="tooltip">Reviver</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('DecompressorController@index') }}" title="Decompress Ricochet levels to view their raw text data" data-toggle="tooltip">Decompressor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('AboutController@index') }}" title="Learn more about this website" data-toggle="tooltip">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-discord" href="https://discord.gg/fKK42Wt" title="Join other players on the Ricochet Infinity Players Discord" data-toggle="tooltip">
                                @include('icons.discord')<span class="d-md-none ml-2">Discord</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-gitlab" href="https://gitlab.com/ngyikp/ricochet-levels" title="View the website source code on GitLab" data-toggle="tooltip">
                                @include('icons.gitlab')<span class="d-md-none ml-2">GitLab</span>
                            </a>
                        </li>
                    </ul>

                    <form class="form-inline my-2 my-md-0 ml-md-2" method="GET" action="{{ action('LevelController@index') }}">
                        <input class="form-control mr-2 navbar-search" type="search" name="search" placeholder="Search levels" aria-label="Search levels" value="{{ request()->input('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </form>

                    @auth
                        <ul class="navbar-nav ml-2">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                                   id="accountNavbarDropdownMenuLink" role="button" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false" title="{{ Auth::user()->name }}">
                                    <img src="{{ Auth::user()->discord_avatar_url }}"
                                         width="24"
                                         height="24"
                                         alt="{{ Auth::user()->name }}’s profile picture"
                                         class="mr-1">
                                </a>

                                <div class="dropdown-menu dropdown-menu-right"
                                     aria-labelledby="accountNavbarDropdownMenuLink">
                                    <h6 class="dropdown-header">{{ Auth::user()->name }}</h6>
                                    <div class="dropdown-divider"></div>

                                    <form action="{{ action('AuthController@logout') }}" method="POST">
                                        {{ csrf_field() }}

                                        <input type="submit" class="dropdown-item" value="Sign out">
                                    </form>
                                </div>
                            </li>
                        </ul>
                    @endauth
                </div>
        </nav>

        @include('layouts.errors')

        <main class="py-3">
            @yield('content')
        </main>
    </div>

    <script src="{{ mix('app.js') }}" async></script>
</body>
</html>
