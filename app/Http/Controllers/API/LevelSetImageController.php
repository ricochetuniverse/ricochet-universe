<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LevelSetImageController extends Controller
{
    /**
     * Early RLW levels (before 2006-11) stored their level set images at `images/`
     *
     * We haven't captured/saved these images to the server yet, so redirect to archive.org and hope they got a saved
     * copy
     *
     * @param Request $request
     * @param string $fileName
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion1(Request $request, string $fileName)
    {
        return $this->redirect(
            $request->isSecure(),
            $this->getArchiveOrgFallbackUrl() . 'images/' . $fileName . '.jpg'
        );
    }

    /**
     * These level sets use thumbnails from the rounds
     *
     * @param Request $request
     * @param string $name
     * @param int $number
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion2(Request $request, string $name, int $number)
    {
        $fileName = $name . '/' . $number . '.jpg';

        $disk = Storage::disk('round-images');
        if ($disk->exists($fileName)) {
            return $this->redirect($request->isSecure(), $disk->url($fileName));
        }

        return $this->redirect($request->isSecure(), $this->getArchiveOrgFallbackUrl() . 'cache/' . $fileName);
    }

    /**
     * @param bool $isSecure
     * @param string $url
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function redirect(bool $isSecure, string $url)
    {
        if (!$isSecure) {
            $url = preg_replace('/^https\:\/\//', 'http://', $url);
        }

        return redirect($url);
    }

    /**
     * @return string
     */
    private function getArchiveOrgFallbackUrl(): string
    {
        return 'https://web.archive.org/web/20171205000449im_/http://www.ricochetInfinity.com/levels/';
    }
}
