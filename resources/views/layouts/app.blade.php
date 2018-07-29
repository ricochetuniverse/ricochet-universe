<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="viewport" content="width=device-width">
<title>{{ config('app.name') }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ mix('app.scss') }}" rel="stylesheet">
<script src="{{ mix('app.js') }}" defer></script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">Ricochet</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ action('LevelController@index') }}">Levels</a>
                        </li>
                    </ul>

                    <form class="form-inline my-2 my-lg-0" method="GET" action="{{ action('LevelController@index') }}">
                        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search levels" aria-label="Search levels" value="{{ request()->input('search') }}">
                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
