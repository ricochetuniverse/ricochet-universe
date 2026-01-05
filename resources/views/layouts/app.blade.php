<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}@if (Request::is('/')) - Download and play custom Ricochet Infinity levels @endif</title>
    <meta name="viewport" content="width=device-width">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="theme-color" content="#00fffe">
    <meta name="color-scheme" content="dark light">
    @hasSection('robots')<meta name="robots" content="@yield('robots')">@endif

    <link href="{{ \App\Helpers\MixManifestWithIntegrity::getPath('app.css') }}" rel="stylesheet" integrity="{{ \App\Helpers\MixManifestWithIntegrity::getIntegrity('app.css') }}" crossorigin="anonymous">

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
    <link href="{{ action('API\OpensearchController@index') }}" rel="search"
          type="application/opensearchdescription+xml" title="Ricochet Levels Search">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md bg-dark" data-bs-theme="dark">
            <div class="container-fluid">
                <div class="d-none d-md-block">
                    <a class="navbar-brand d-flex me-2" href="{{ action('HomeController@index') }}" title="{{ config('app.name') }}" data-bs-toggle="tooltip">
                        <img src="{{ asset('images/ricochet-logo.png') }}" width="30" height="30" alt="{{ config('app.name') }}">
                    </a>
                </div>

                <div class="d-md-none">
                    <a class="navbar-brand d-flex me-2" href="{{ action('HomeController@index') }}">
                        <img src="{{ asset('images/ricochet-logo.png') }}" width="30" height="30" alt="{{ config('app.name') }}">
                        <span class="ms-2">{{ config('app.name') }}</span>
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <div id="levelsNavbarDropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <a href="#"
                                   class="nav-link dropdown-toggle @if (($selected_navbar_item ?? '') === 'levels')active @endif"
                                   title="Explore, download and play level sets created by the community"
                                   data-bs-toggle="tooltip"
                                   role="button">
                                    Levels
                                </a>
                            </div>

                            <ul class="dropdown-menu" aria-labelledby="levelsNavbarDropdownMenuLink">
                                <li><a href="{{ action('LevelController@index') }}" class="dropdown-item">Most downloaded</a></li>
                                <li><a href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => 'DESC']) }}" class="dropdown-item">Latest</a>
                                <li><hr class="dropdown-divider"></li>

                                <li><a href="{{ action('RoundsController@index') }}" class="dropdown-item">Search rounds</a></li>
                                <li><hr class="dropdown-divider"></li>

                                <li><a href="{{ action('UploadController@index') }}" class="dropdown-item">Upload</a></li>
                            </ul>
                        </li>
                        @include('layouts.navbar-item', [
                            'key' => 'mods',
                            'href' => action('ModsController@index'),
                            'title' => 'Play new environments, custom content and modifications',
                            'text' => 'Mods'
                        ])
                        @include('layouts.navbar-item', [
                            'key' => 'reviver',
                            'href' => action('ReviverController@index'),
                            'title' => 'Restore the in-game level catalog in Ricochet Infinity',
                            'text' => 'Reviver'
                        ])
                        @include('layouts.navbar-item', [
                            'key' => 'tools',
                            'href' => action('ToolsController@index'),
                            'title' => 'Use various utilities that are useful for tinkerers',
                            'text' => 'Tools'
                        ])
                        @include('layouts.navbar-item', [
                            'key' => 'wiki',
                            'href' => 'https://wiki.ricochetuniverse.com',
                            'title' => 'Learn more about the Ricochet game series in the fan wiki',
                            'text' => 'Wiki'
                        ])
                        @include('layouts.navbar-item', [
                            'key' => 'about',
                            'href' => action('AboutController@index'),
                            'title' => 'Learn more about this website',
                            'text' => 'About'
                        ])
                        <li class="nav-item">
                            <a class="nav-link nav-link-discord" href="{{ action('DiscordRedirectController@index') }}" title="Join other players on the Ricochet Players Discord" data-bs-toggle="tooltip">
                                @include('icons.discord')<span class="d-md-none ms-2">Discord</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-gitlab" href="https://gitlab.com/ngyikp/ricochet-levels" title="View the website source code on GitLab" data-bs-toggle="tooltip">
                                @include('icons.gitlab')<span class="d-md-none ms-2">GitLab</span>
                            </a>
                        </li>
                    </ul>

                    <div class="d-none d-md-block flex-grow-1">
                        <form class="d-flex flex-grow-1 justify-content-end my-0 ms-2" method="GET" action="{{ action('LevelController@index') }}">
                            <input class="form-control navbar-search" type="search" name="search" placeholder="Search level sets by name/author" title="Search level sets by name/author" value="{{ $navbar_search ?? '' }}">
                            <button class="btn btn-outline-primary ms-2" type="submit">Search</button>
                        </form>
                    </div>

                    @auth
                        <ul class="navbar-nav ms-md-2">
                            <li class="nav-item dropdown">
                                <div id="accountNavbarDropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <a href="#"
                                       class="nav-link dropdown-toggle d-flex align-items-center"
                                       title="Signed in as {{ Auth::user()->name }}"
                                       data-bs-toggle="tooltip"
                                       role="button">
                                        <img src="{{ Auth::user()->getAvatarUrl(64) }}"
                                             width="24"
                                             height="24"
                                             alt="{{ Auth::user()->name }}’s avatar"
                                             class="navbar-avatar">
                                    </a>
                                </div>

                                <div class="dropdown-menu dropdown-menu-end"
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
            </div>
        </nav>

        @include('layouts.errors')

        <main class="py-3">
            @yield('content')
        </main>
    </div>

    <script src="{{ \App\Helpers\MixManifestWithIntegrity::getPath('app.js') }}" async integrity="{{ \App\Helpers\MixManifestWithIntegrity::getIntegrity('app.js') }}" crossorigin="anonymous"></script>
</body>
</html>
