<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\LevelSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LevelDownloadController extends Controller
{
    public function download(Request $request)
    {
        $file = $request->input('File');

        $file = str_after($file, 'downloads/raw/');
        $file = str_before($file, '.RicochetLW');
        $file = str_before($file, '.RicochetI');

        $levelSet = LevelSet::where('name', $file)->firstOrFail();

        $disk = Storage::disk('levels');
        $fileName = $levelSet->name . $levelSet->getFileExtension();

        if ($disk->exists($fileName)) {
            return redirect($disk->url($fileName));
        }

        if ($levelSet->alternate_download_url) {
            return redirect($levelSet->alternate_download_url);
        }

        throw new NotFoundHttpException;
    }
}
