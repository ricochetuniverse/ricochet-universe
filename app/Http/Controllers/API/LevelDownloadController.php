<?php

namespace App\Http\Controllers\API;

use App\Helpers\RedirectForGame;
use App\Helpers\TextEncoderForGame;
use App\Http\Controllers\Controller;
use App\LevelSet;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LevelDownloadController extends Controller
{
    public function download(Request $request, FilesystemManager $storage)
    {
        $file = $request->input('File', '');

        if (strlen($file) <= 0) {
            throw new NotFoundHttpException;
        }

        // First try the usual UTF-8, then try decode legacy encoding to UTF-8
        $levelSet = $this->tryUtf8($file);
        if (!$levelSet) {
            $levelSet = $this->tryLegacyEncoding($file);
            if (!$levelSet) {
                throw new NotFoundHttpException;
            }
        }

        $fileName = $levelSet->name . $levelSet->getFileExtension();
        $fileUrl = rawurlencode($levelSet->name) . $levelSet->getFileExtension();

        $disk = $storage->disk('levels');
        if ($disk->exists($fileName)) {
            return RedirectForGame::to($request->isSecure(), $disk->url($fileUrl));
        }

        if ($levelSet->alternate_download_url) {
            // Alternate download server wouldn't accept non-HTTPS...
            return redirect($levelSet->alternate_download_url);
        }

        throw new NotFoundHttpException;
    }

    /**
     * @param string $file
     * @return LevelSet|\Illuminate\Database\Eloquent\Model|null|object
     */
    private function tryUtf8(string $file)
    {
        $file = $this->stripFileParameterPrefixAndSuffix($file);

        return LevelSet::where('name', $file)->first();
    }

    /**
     * @param string $file
     * @return LevelSet|\Illuminate\Database\Eloquent\Model|null|object
     */
    private function tryLegacyEncoding(string $file)
    {
        return $this->tryUtf8(TextEncoderForGame::toUtf8($file));
    }

    /**
     * @param string $file
     * @return string
     */
    private function stripFileParameterPrefixAndSuffix(string $file): string
    {
        $file = Str::after($file, 'downloads/raw/');
        $file = Str::beforeLast($file, '.RicochetLW');
        $file = Str::beforeLast($file, '.RicochetI');

        return $file;
    }
}
