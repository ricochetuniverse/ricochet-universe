<?php

namespace App\Http\Controllers\API;

use App\Helpers\RedirectForGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Url\Url;

class LevelSetImageController extends Controller
{
    /**
     * Early RLW levels (before 2006-11) stored their level set images at `images/`
     *
     * We haven't captured/saved these images to the server yet, so redirect to archive.org and hope they got a saved
     * copy
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion1(Request $request, string $fileName)
    {
        return RedirectForGame::to(
            $request->isSecure(),
            $this->getArchiveOrgFallbackUrl().'images/'.$fileName.'.jpg'
        );
    }

    /**
     * These level sets use thumbnails from the rounds
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion2(Request $request, string $name, int $number)
    {
        $isSecure = $request->isSecure();

        $fileName = $name.'/'.$number.'.jpg';
        $fileUrl = rawurlencode($name).'/'.$number.'.jpg';

        $disk = Storage::disk('round-images');
        if ($disk->exists($fileName)) {
            $url = Url::fromString($disk->url($fileUrl))
                ->withQueryParameter('time', $disk->lastModified($fileName));

            return RedirectForGame::to($isSecure, $url);
        }

        return RedirectForGame::to($isSecure, $this->getArchiveOrgFallbackUrl().'cache/'.$fileUrl);
    }

    private function getArchiveOrgFallbackUrl(): string
    {
        return 'https://web.archive.org/web/20171205000449im_/http://www.ricochetInfinity.com/levels/';
    }
}
