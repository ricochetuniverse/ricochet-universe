<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}@if (Request::is('/')) - Download and play custom Ricochet Infinity levels @endif</title>
    <meta name="viewport" content="width=device-width">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="theme-color" content="#00fffe">
    <meta name="color-scheme" content="dark light">
    @hasSection('robots')<meta property="robots" content="@yield('robots')">@endif

    <link href="{{ mix('app.css') }}" rel="stylesheet">

    @hasSection('og:url')<meta name="canonical" content="@yield('og:url')">@endif

    @hasSection('og:title')<meta property="og:title" content="@yield('og:title')">@endif
    {{--<meta property="og:type" content="website">--}}
    @hasSection('og:url')<meta property="og:url" content="@yield('og:url')">@endif
    <meta property="og:description" content="@yield('description', 'Explore, download and play Ricochet Infinity and Ricochet Lost Worlds level sets created by the community')">
    <meta name="description" content="@yield('description', 'Explore, download and play Ricochet Infinity and Ricochet Lost Worlds level sets created by the community')">
    @hasSection('og:image')
        <meta property="og:image" content="@yield('og:image')">
        <meta name="thumbnail" content="@yield('og:image')">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">

    @if (config('ricochet.google_analytics_id'))
        <meta id="google-analytics-tracking-id" content="{{ config('ricochet.google_analytics_id') }}">
    @endif
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}

    <link href="{{ action('API\RssController@index') }}" rel="alternate" type="application/rss+xml">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="d-none d-md-block">
                <a class="navbar-brand js-with-tooltip d-flex mr-2" href="{{ action('HomeController@index') }}" title="{{ config('app.name') }}">
                    <img src="{{ asset('images/ricochet-logo.png') }}" width="30" height="30" alt="{{ config('app.name') }}">
                </a>
            </div>

            <div class="d-md-none">
                <a class="navbar-brand d-flex mr-2" href="{{ action('HomeController@index') }}">
                    <img src="{{ asset('images/ricochet-logo.png') }}" width="30" height="30" alt="{{ config('app.name') }}"><span class="ml-2">{{ config('app.name') }}</span>
                </a>
            </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown @if (($selected_navbar_item ?? '') === 'levels')active @endif">
                            <a href="#" class="nav-link dropdown-toggle js-with-tooltip"
                               id="levelsNavbarDropdownMenuLink" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false"
                               title="Explore, download and play level sets created by the community">
                                Levels
                            </a>

                            <div class="dropdown-menu" aria-labelledby="levelsNavbarDropdownMenuLink">
                                <a href="{{ action('LevelController@index') }}" class="dropdown-item">Most downloaded</a>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => 'DESC']) }}" class="dropdown-item">Latest</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ action('UploadController@index') }}" class="dropdown-item">Upload</a>
                            </div>
                        </li>
                        <li class="nav-item @if (($selected_navbar_item ?? '') === 'mods')active @endif">
                            <a class="nav-link js-with-tooltip" href="{{ action('ModsController@index') }}" title="Play new environments, custom content and modifications">Mods</a>
                        </li>
                        <li class="nav-item @if (($selected_navbar_item ?? '') === 'reviver')active @endif">
                            <a class="nav-link js-with-tooltip" href="{{ action('ReviverController@index') }}" title="Restore the in-game level catalog in Ricochet Infinity">Reviver</a>
                        </li>
                        <li class="nav-item @if (($selected_navbar_item ?? '') === 'tools')active @endif">
                            <a class="nav-link js-with-tooltip" href="{{ action('ToolsController@index') }}" title="Use various utilities that are useful for tinkerers">Tools</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-with-tooltip" href="https://wiki.ricochetuniverse.com" title="Learn more about the Ricochet game series in the fan wiki">Wiki</a>
                        </li>
                        <li class="nav-item @if (($selected_navbar_item ?? '') === 'about')active @endif">
                            <a class="nav-link js-with-tooltip" href="{{ action('AboutController@index') }}" title="Learn more about this website">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-with-tooltip nav-link-discord" href="{{ action('DiscordRedirectController@index') }}" title="Join other players on the Ricochet Players Discord">
                                @include('icons.discord')<span class="d-md-none ml-2">Discord</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-with-tooltip nav-link-gitlab" href="https://gitlab.com/ngyikp/ricochet-levels" title="View the website source code on GitLab">
                                @include('icons.gitlab')<span class="d-md-none ml-2">GitLab</span>
                            </a>
                        </li>
                    </ul>

                    <div class="d-none d-md-flex flex-grow-1">
                        <form class="form-inline my-2 my-md-0 ml-md-2 flex-grow-1 justify-content-end" method="GET" action="{{ action('LevelController@index') }}">
                            <input class="form-control navbar-search" type="search" name="search" placeholder="Search level sets by name/author" title="Search level sets by name/author" value="{{ request()->input('search') }}">
                            <button class="btn btn-outline-primary ml-2" type="submit">Search</button>
                        </form>
                    </div>

                    @auth
                        <ul class="navbar-nav ml-md-2">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                                   id="accountNavbarDropdownMenuLink" role="button" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false" title="{{ Auth::user()->name }}">
                                    <img src="{{ Auth::user()->getAvatarUrl(64) }}"
                                         width="24"
                                         height="24"
                                         alt="{{ Auth::user()->name }}’s avatar"
                                         class="navbar-avatar">
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
