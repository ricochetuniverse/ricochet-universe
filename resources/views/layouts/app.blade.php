<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="viewport" content="width=device-width">
<title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ mix('app.scss') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a class="navbar-brand" href="{{ url('/') }}">Ricochet</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('LevelController@index') }}">Levels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">About</a>
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
