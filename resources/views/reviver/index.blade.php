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
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <x-card>
                    <x-card.header>Restore the in-game level catalog</x-card.header>

                    <x-card.body>
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
                                    <x-button href="{{ action('ReviverController@show', ['os' => $groupId]) }}"
                                              :active="isset($os) && $os === $groupId">
                                        {{ $groupName }}
                                    </x-button>
                                @endforeach
                            </div>
                        </div>
                    </x-card.body>
                </x-card>
            </div>
        </div>

        @if (isset($os))
            <div class="row">
                <div class="col vstack gap-3">
                    @if ($os === \App\Http\Controllers\ReviverController::MACOS)
                        @include('reviver._macos')
                    @else
                        @include('reviver._windows')
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <x-card>
                        <x-card.header tag="h2">Finished!</x-card.header>

                        <x-card.body>
                            <p class="m-0">
                                That’s all the steps needed to restore the in-game level catalog. Launch the game and
                                enjoy!
                            </p>
                        </x-card.body>
                    </x-card>
                </div>
            </div>
        @endif
    </div>
@endsection
