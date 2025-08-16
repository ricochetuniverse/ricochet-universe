@extends('layouts.app', [
    'selected_navbar_item' => 'reviver.index.blade.php.reviver',
])

@section('title', 'Reviver')
@if (isset($os))
    @section('og:url', action('ReviverController@show', ['os' => $os]))
@else
    @section('og:url', action('ReviverController@index'))
@endif
@section('description', 'Restore the in-game level catalog in Ricochet Infinity.')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Restore the in-game level catalog</div>

                    <div class="card-body">
                        <p>
                            Follow these steps to restore the in-game level catalog on Ricochet Infinity so you can
                            browse and download new levels inside the game.
                        </p>

                        <p>
                            If you need help with any of these instructions, you can
                            <a href="{{ action('DiscordRedirectController@index') }}">join our Discord</a> where others
                            can help you out!
                        </p>

                        <div>
                            Select your operating system:

                            <div class="btn-group" role="group">
                                @foreach (\App\Http\Controllers\ReviverController::GROUPS as $groupId => $groupName)
                                    <a href="{{ action('ReviverController@show', ['os' => $groupId]) }}"
                                       class="btn btn-outline-primary {{ isset($os) && $os === $groupId ? 'active' : '' }}"
                                       @if (isset($os) && $os === $groupId) aria-pressed="true"@endif>
                                        {{ $groupName }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @if (isset($os))
                    @if ($os === \App\Http\Controllers\ReviverController::MACOS)
                        @include('reviver._macos')
                    @else
                        @include('reviver._windows')
                    @endif

                    <div class="card">
                        <div class="card-header">Finished!</div>

                        <div class="card-body">
                            <p class="m-0">
                                That’s all the steps needed to restore the in-game level catalog. Launch the game and
                                enjoy!
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
