<?php

namespace App\Http\Controllers\API;

use App\Helpers\RedirectForGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Url\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LevelSetImageController extends Controller
{
    public const string FALLBACK_URL = 'https://web.archive.org/web/20171205000449im_/http://www.ricochetInfinity.com/levels/';
    // public const string FALLBACK_URL = 'https://web.archive.org/web/20171205000449im_/http://www.ricochetlostworlds.com/levels/';

    /**
     * Early RLW levels uploaded before November 2006 are screenshot manually by the level approvers, rather than
     * picking an existing level thumbnail, these images are archived at
     * https://web.archive.org/web/%2A/http://www.ricochetInfinity.com/levels/images/%2A
     * https://web.archive.org/web/%2A/http://www.ricochetlostworlds.com/levels/images/%2A
     *
     * First, check if we already saved these images on our server
     * If not, redirect to archive.org and hope they got a saved copy (likely unsuccessful as we already attempted to
     * save them all)
     *
     * URL path: /levels/images/{name}.jpg
     *
     * @see https://gitlab.com/ngyikp/ricochet-levels/-/issues/14
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion1(Request $request, string $name)
    {
        // 9 level sets have this...
        if ($name === 'none') {
            throw new NotFoundHttpException;
        }

        $isSecure = $request->isSecure();

        $fileName = rawurldecode($name).'.jpg';

        $disk = Storage::disk('legacy-levelset-images');
        if ($disk->exists($fileName)) {
            return RedirectForGame::to(
                $isSecure,
                Url::fromString($disk->url($fileName))
                    ->withQueryParameter('time', $disk->lastModified($fileName))
            );
        }

        // todo remove this redirect after archive is finished
        return RedirectForGame::to($isSecure, self::FALLBACK_URL.'images/'.$name.'.jpg');
    }

    /**
     * These level sets use thumbnails from the rounds
     *
     * URL path: /levels/cache/{name}/{number}.jpg
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

        // todo is this redirect really needed? can we just fail it?
        // throw new \Exception('Level set image '.$fileName.' not found');
        return RedirectForGame::to($isSecure, self::FALLBACK_URL.'cache/'.$fileUrl);
    }
}
