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
                        {{--<li class="nav-item" title="Upload and share your level sets" data-toggle="tooltip">
                            <a class="nav-link" href="{{ action('UploadController@index') }}">Upload</a>
                        </li>--}}
                        <li class="nav-item" title="Restore the in-game level catalog in Ricochet Infinity" data-toggle="tooltip">
                            <a class="nav-link" href="{{ action('ReviverController@index') }}">Reviver</a>
                        </li>
                        <li class="nav-item" title="Learn more about this website" data-toggle="tooltip">
                            <a class="nav-link" href="{{ action('AboutController@index') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-discord" href="https://discord.gg/fKK42Wt" title="Discord" data-toggle="tooltip">
                                @include('icons.discord')<span class="d-md-none ml-2">Discord</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-gitlab" href="https://gitlab.com/ngyikp/ricochet-levels" title="GitLab" data-toggle="tooltip">
                                @include('icons.gitlab')<span class="d-md-none ml-2">GitLab</span>
                            </a>
                        </li>
                    </ul>

                    <form class="form-inline my-2 my-md-0" method="GET" action="{{ action('LevelController@index') }}">
                        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search levels" aria-label="Search levels" value="{{ request()->input('search') }}">
                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
        </nav>

        <main class="py-3">
            @yield('content')
        </main>
    </div>

    <script src="{{ mix('app.js') }}" async></script>
</body>
</html>
