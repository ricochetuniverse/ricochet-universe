<?php

namespace App\Http\Controllers\API;

use App\Helpers\GameUserAgent;
use App\Helpers\RedirectForGame;
use App\Helpers\TextEncoderForGame;
use App\Http\Controllers\Controller;
use App\Jobs\CreateLevelSetDownloadLog;
use App\LevelSet;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
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
        if (! $disk->exists($fileName)) {
            throw new NotFoundHttpException;
        }

        $this->maybeAddDownloadCount($request, $levelSet);

        $url = Uri::of($disk->url($fileUrl))
            ->withQuery(['time' => $disk->lastModified($fileName)]);

        return RedirectForGame::to($request->isSecure(), $url);
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

    private function maybeAddDownloadCount(Request $request, LevelSet $levelSet): void
    {
        // Don't count hits from known bots
        if (Str::contains($request->userAgent(), 'bot', true)) {
            return;
        }

        // Only count Ricochet user-agents OR modern browsers that send Sec-Fetch-Site header
        //
        // Every browser send slightly different headers
        //
        // Chrome 136:
        //   Left-click:
        //     Sec-Fetch-Dest: document
        //     Sec-Fetch-Mode: navigate
        //     Sec-Fetch-Site: same-origin
        //     Sec-Fetch-User: ?1
        //   Right-click > Save Link As:
        //     Sec-Fetch-Dest: empty
        //     Sec-Fetch-Mode: navigate
        //     Sec-Fetch-Site: same-origin
        //
        // Firefox 138:
        //   Left-click:
        //     Sec-Fetch-Dest: document
        //     Sec-Fetch-Mode: navigate
        //     Sec-Fetch-Site: same-origin
        //     Sec-Fetch-User: ?1
        //   Right-click > Save Link As:
        //     Sec-Fetch-Dest: empty
        //     Sec-Fetch-Mode: no-cors
        //     Sec-Fetch-Site: same-origin
        //
        // Safari 18.4:
        //   Left-click:
        //     Sec-Fetch-Dest: document
        //     Sec-Fetch-Mode: navigate
        //     Sec-Fetch-Site: same-origin
        //   Right-click > Download Linked File:
        //     No Sec-Fetch headers are sent
        //
        if (! GameUserAgent::checkRequest($request) && $request->header('Sec-Fetch-Site') !== 'same-origin') {
            return;
        }

        CreateLevelSetDownloadLog::dispatchAfterResponse($levelSet->id, $request->ip());
    }
}
