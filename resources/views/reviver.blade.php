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
                            Go to
                            <a href="https://skanthak.homepage.t-online.de/curl.html#installation">https://skanthak.homepage.t-online.de/curl.html#installation</a>
                            and download the <code>curl-*.**.*.cab</code> file.
                        </p>

                        <p>
                            Open the downloaded CAB file, copy <code>LIBCURL.DLL</code> from <code>I386\</code> and
                            paste it to the Ricochet Infinity program folder.
                        </p>

                        <p>
                            If Windows File Explorer shows 3 confusing copies of <code>LIBCURL.DLL</code> when you open
                            the CAB file, please change the <span class="text-nowrap">View to Details</span>, and then
                            <span class="text-nowrap">Group by &gt; Path</span>. Refer to this video if you’re not sure
                            what to do:
                        </p>

                        <div class="embed-responsive reviverPage__curl__video">
                            <video src="{{ asset('video/ricochet-libcurl.mp4') }}"
                                   width="1052"
                                   height="698"
                                   controls
                                   muted
                                   poster="{{ asset('images/ricochet-libcurl-video-poster.png') }}"
                                   preload="none"
                                   class="embed-responsive-item"></video>
                        </div>
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

                <div class="card mb-3">
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
