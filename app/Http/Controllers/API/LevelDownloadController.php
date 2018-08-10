<?php

namespace App\Http\Controllers\API;

use App\Helpers\RedirectForGame;
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

        $fileName = $levelSet->name . $levelSet->getFileExtension();
        $fileUrl = rawurlencode($levelSet->name) . $levelSet->getFileExtension();

        $disk = Storage::disk('levels');
        if ($disk->exists($fileName)) {
            return RedirectForGame::to($request->isSecure(), $disk->url($fileUrl));
        }

        if ($levelSet->alternate_download_url) {
            // Alternate download server wouldn't accept non-HTTPS...
            return redirect($levelSet->alternate_download_url);
        }

        throw new NotFoundHttpException;
    }
}
