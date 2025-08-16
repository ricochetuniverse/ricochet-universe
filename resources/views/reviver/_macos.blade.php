<div class="alert alert-warning mb-3" role="alert">
    <p>
        The Mac version of Ricochet Infinity
        <a href="https://support.apple.com/en-us/103076" class="alert-link">does not work on macOS Catalina 10.15
            or later</a>.
    </p>

    <p>
        If you don’t have an old Mac, you need to use alternatives to play the Windows version of Ricochet Infinity.
    </p>

    <p class="mb-0">
        After you finish set up,
        <a href="{{ action('ReviverController@show', ['os' => \App\Http\Controllers\ReviverController::WINDOWS10]) }}"
           class="alert-link">refer to the Windows instructions to restore the level catalog</a>.
    </p>
</div>

<div class="card mb-3">
    <div class="card-header">Edit Data2.dat</div>

    <div class="card-body">
        <p>
            Launch the game, right-click the game’s icon on your Mac Dock and click
            <span class="text-nowrap">Options &gt; Show in Finder</span>.
        </p>

        <p>
            A Finder window will open with the game selected, right-click the game and click
            <span class="text-nowrap">Show Package Contents</span>.
        </p>

        <p>
            Open the <code>Contents</code> and <code>Resources</code> folders, then find
            the <code>Data2.dat</code> file.
        </p>

        <details class="mb-2">
            <summary>If Data2.dat exists</summary>

            <p>
                Right-click <code>Data2.dat</code> and click <span class="text-nowrap">Open With &gt; Other...</span>
            </p>

            <p>Choose the TextEdit app and click Open.</p>

            <p class="m-0">Find this line at the bottom:</p>
            <div class="mb-3 cursor-auto">
                <code>Catalog URL=http://www.ricochetinfinity.com/gateway/catalog.php</code>
            </div>

            <p class="m-0">Change it to:</p>
            <div class="mb-3 cursor-auto">
                <code>Catalog
                    URL={{ preg_replace('/^https:\/\//', 'http://', action('API\\CatalogController@index')) }}</code>
            </div>

            <p class="m-0">
                Save and close TextEdit.
            </p>
        </details>

        <details>
            <summary>If Data2.dat doesn’t exist</summary>

            <p class="m-0">
                Download
                <a href="{{ action('ReviverController@generateData2DatFile') }}">this replacement Data2.dat file</a>
                and move it inside the <code>Resources</code> folder.
            </p>
        </details>
    </div>
</div>
