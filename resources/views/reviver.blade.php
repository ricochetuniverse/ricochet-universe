@extends('layouts.app')

@section('title', 'Reviver')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Restore the in-game level catalog</div>

                    <div class="card-body">
                        <p class="m-0">
                            Follow these steps to restore the in-game level catalog so you can browse and download new
                            levels right in the game.
                        </p>
                    </div>
                </div>

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
                            (version 7.61.1), then copy and paste it to the Ricochet Infinity program folder.
                        </p>

                        <p class="m-0">
                            (The original download link is located at
                            <a href="https://skanthak.homepage.t-online.de/curl.html#installation">https://skanthak.homepage.t-online.de/curl.html#installation</a>,
                            feel free to use that link and/or send it to VirusTotal)
                        </p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Edit Data2.dat</div>

                    <div class="card-body">
                        <p>Find <code>Data2.dat</code> in the Ricochet Infinity program folder.</p>

                        <p>Right-click it, and then <span class="text-nowrap">Open with &gt; Notepad</span>.</p>

                        <p class="m-0">Find this line at the bottom:</p>
                        <div class="mb-3">
                            <code>Catalog URL=http://www.ricochetinfinity.com/gateway/catalog.php</code>
                        </div>

                        <p class="m-0">Change it to:</p>
                        <div class="mb-3">
                            <code>Catalog URL=https://ricochet.ngyikp.com/gateway/catalog.php</code>
                        </div>

                        <p class="m-0">Save and close Notepad.</p>
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
            </div>
        </div>
    </div>
@endsection
