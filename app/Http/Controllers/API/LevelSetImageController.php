<?php

namespace App\Http\Controllers\API;

use App\Helpers\RedirectForGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Uri;
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
     * URL path: /levels/images/{name}.jpg
     *
     * @see https://gitlab.com/ngyikp/ricochet-levels/-/issues/14
     */
    public function showVersion1(Request $request, string $name): RedirectResponse
    {
        // 9 level sets have this...
        if ($name === 'none') {
            throw new NotFoundHttpException;
        }

        $fileName = rawurldecode($name).'.jpg';

        // We already saved images from archive.org on our server, redirecting to archive.org wpn't help
        $disk = Storage::disk('legacy-levelset-images');
        if (! $disk->exists($fileName)) {
            throw new NotFoundHttpException;
        }

        $url = Uri::of($disk->url($fileName))
            ->withQuery(['time', $disk->lastModified($fileName)]);

        return $this->setCacheHeaders(RedirectForGame::to($request->isSecure(), $url));
    }

    /**
     * These level sets use thumbnails from the rounds
     *
     * URL path: /levels/cache/{name}/{number}.jpg
     */
    public function showVersion2(Request $request, string $name, int $number): RedirectResponse
    {
        $fileName = $name.'/'.$number.'.jpg';
        if (str_contains($fileName, '%20')) {
            // filter out dumb scrapers that don't understand URL encoding
            throw new NotFoundHttpException;
        }

        $disk = Storage::disk('round-images');
        if (! $disk->exists($fileName)) {
            throw new NotFoundHttpException;
            // $url = self::FALLBACK_URL.'cache/'.$fileUrl;
        }

        $fileUrl = rawurlencode($name).'/'.$number.'.jpg';
        $url = Uri::of($disk->url($fileUrl))
            ->withQuery(['time' => $disk->lastModified($fileName)]);

        return $this->setCacheHeaders(RedirectForGame::to($request->isSecure(), $url));
    }

    /**
     * Laravel's default SetCacheHeaders middleware does not run on redirects,
     * see https://github.com/laravel/framework/commit/94bfff1d057c9c53d24ad0c3b66294e4c0a81bb7
     */
    private function setCacheHeaders(RedirectResponse $redirect): RedirectResponse
    {
        return $redirect->setCache([
            'public' => true,
            'max_age' => 600,
        ]);
    }
}
