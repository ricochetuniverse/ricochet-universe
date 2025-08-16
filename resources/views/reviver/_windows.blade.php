@if ($os === \App\Http\Controllers\ReviverController::WINDOWS10)
    <div class="card mb-3">
        <div class="card-header">Update curl</div>

        <div class="card-body">
            <p>
                curl is software code used by many applications to perform networking features. You can learn more
                about curl by reading its <a href="https://en.wikipedia.org/wiki/CURL">Wikipedia article</a>.
            </p>

            <p>
                The curl version bundled with Ricochet Infinity is outdated and cannot handle modern HTTPS
                connections, we need to update curl to connect to today’s web.
            </p>

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
        <p>Find <code>Data2.dat</code> in the Ricochet Infinity program folder.</p>

        <p>Right-click it and click <span class="text-nowrap">Open with &gt; Notepad</span>.</p>

        <p class="m-0">Find this line at the bottom:</p>
        <div class="mb-3 cursor-auto">
            <code>Catalog URL=http://www.ricochetinfinity.com/gateway/catalog.php</code>
        </div>

        <p class="m-0">Change it to:</p>
        <div class="mb-3 cursor-auto">
            @if ($os === \App\Http\Controllers\ReviverController::WINDOWS10)
                <code>Catalog URL={{ action('API\\CatalogController@index') }}</code>
            @else
                <code>Catalog
                    URL={{ preg_replace('/^https:\/\//', 'http://', action('API\\CatalogController@index')) }}</code>
            @endif
        </div>

        <p class="m-0">
            Save and close Notepad.
        </p>
    </div>
</div>
