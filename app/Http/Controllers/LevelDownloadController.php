<?php

namespace App\Http\Controllers;

use App\LevelSet;
use Illuminate\Http\Request;

class LevelDownloadController extends Controller
{
    public function download(Request $request)
    {
        $file = $request->input('File');

        $file = str_after($file, 'downloads/raw/');
        $file = str_before($file, '.RicochetLW');
        $file = str_before($file, '.RicochetI');

        $level = LevelSet::whereName($file)->firstOrFail();

        return redirect($level->alternate_download_url);
    }
}
