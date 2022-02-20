@extends('layouts.app', [
    'selected_navbar_item' => 'reviver',
])

@section('title', 'Reviver')
@section('description', 'Restore the in-game level catalog in Ricochet Infinity.')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Restore the in-game level catalog</div>

                    <div class="card-body">
                        <p>
                            Follow these steps to restore the in-game level catalog so you can browse and download new
                            levels right in the game.
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
                    @if ($os === \App\Http\Controllers\ReviverController::WINDOWS10)
                        <div class="card mb-3">
                            <div class="card-header">Update curl</div>

                            <div class="card-body">
                                <p>
                                    curl is software code used by many applications to perform networking features.
                                    You can learn more about curl by reading its
                                    <a href="https://en.wikipedia.org/wiki/CURL">Wikipedia article</a>.
                                </p>

                                <p>The curl version bundled with Ricochet Infinity is outdated and cannot handle modern HTTPS
                                    connections, we need to update curl to connect to today’s web.</p>

                                <p>
                                    Download the new version of <code><a href="{{ asset('misc/libcurl.dll') }}">libcurl.dll</a></code>
                                    (version 7.64.0), then copy and paste it to the Ricochet Infinity program folder.
                                </p>

                                <p class="m-0">
                                    (The original download link is located at
                                    <a href="https://skanthak.homepage.t-online.de/curl.html#installation">https://skanthak.homepage.t-online.de/curl.html#installation</a>,
                                    feel free to use that link and/or send it to VirusTotal)
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="card mb-3">
                        <div class="card-header">Edit Data2.dat</div>

                        <div class="card-body">
                            @if ($os !== \App\Http\Controllers\ReviverController::MACOS)
                                <p>Find <code>Data2.dat</code> in the Ricochet Infinity program folder.</p>

                                <p>Right-click it and click <span class="text-nowrap">Open with &gt; Notepad</span>.</p>
                            @else
                                <p>
                                    Launch the game, right-click the game’s icon on your Mac Dock and click
                                    <span class="text-nowrap">Options &gt; Show in Finder</span>.
                                </p>

                                <p>
                                    A Finder window will open with the game selected, right-click the game and click
                                    <span class="text-nowrap">Show Package Contents</span>.
                                </p>

                                <p>Open the <code>Contents</code> and <code>Resources</code> folders.</p>

                                <p>
                                    Right-click <code>Data2.dat</code> and click
                                    <span class="text-nowrap">Open With &gt; Other...</span>
                                </p>

                                <p>Choose TextEdit and click Open.</p>

                                <p>Exit the game.</p>
                            @endif

                            <p class="m-0">Find this line at the bottom:</p>
                            <div class="mb-3">
                                <code>Catalog URL=http://www.ricochetinfinity.com/gateway/catalog.php</code>
                            </div>

                            <p class="m-0">Change it to:</p>
                            <div class="mb-3">
                                @if ($os === \App\Http\Controllers\ReviverController::WINDOWS10)
                                    <code>Catalog URL={{ action('API\\CatalogController@index') }}</code>
                                @else
                                    <code>Catalog URL={{ preg_replace('/^https\:\/\//', 'http://', action('API\\CatalogController@index')) }}</code>
                                @endif
                            </div>

                            <p class="m-0">
                                @if ($os !== \App\Http\Controllers\ReviverController::MACOS)
                                    Save and close Notepad.
                                @else
                                    Save and close TextEdit.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">Finished!</div>

                        <div class="card-body">
                            <p class="m-0">
                                That’s all the steps needed to restore the in-game level catalog. Launch the game and enjoy!
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
