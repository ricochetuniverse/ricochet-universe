<?php

namespace App\Http\Controllers\API;

use App\Helpers\RedirectForGame;
use App\Helpers\TextEncoderForGame;
use App\Http\Controllers\Controller;
use App\LevelSet;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Url\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LevelDownloadController extends Controller
{
    public function download(Request $request, FilesystemManager $storage): RedirectResponse
    {
        $file = $request->input('File', '');

        if (! is_string($file) || strlen($file) <= 0) {
            throw new NotFoundHttpException;
        }

        // First try the usual UTF-8, then try to decode legacy encoding to UTF-8
        $levelSet = $this->tryUtf8($file);
        if (! $levelSet) {
            $levelSet = $this->tryLegacyEncoding($file);
            if (! $levelSet) {
                throw new NotFoundHttpException;
            }
        }

        $fileName = $levelSet->name.$levelSet->getFileExtension();
        $fileUrl = rawurlencode($levelSet->name).$levelSet->getFileExtension();

        $disk = $storage->disk('levels');
        if ($disk->exists($fileName)) {
            $url = Url::fromString($disk->url($fileUrl))
                ->withQueryParameter('time', $disk->lastModified($fileName));

            return RedirectForGame::to($request->isSecure(), $url);
        }

        if ($levelSet->alternate_download_url) {
            // Alternate download server wouldn't accept non-HTTPS...
            return redirect($levelSet->alternate_download_url);
        }

        throw new NotFoundHttpException;
    }

    private function tryUtf8(string $file): ?LevelSet
    {
        $file = $this->stripFileParameterPrefixAndSuffix($file);

        return LevelSet::where('name', $file)->first();
    }

    private function tryLegacyEncoding(string $file): ?LevelSet
    {
        return $this->tryUtf8(TextEncoderForGame::toUtf8($file));
    }

    private function stripFileParameterPrefixAndSuffix(string $file): string
    {
        $file = Str::after($file, 'downloads/raw/');
        $file = Str::beforeLast($file, '.RicochetLW');
        $file = Str::beforeLast($file, '.RicochetI');

        return $file;
    }
}
