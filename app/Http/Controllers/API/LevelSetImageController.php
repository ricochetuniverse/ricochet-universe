<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LevelSetImageController extends Controller
{
    /**
     * Early RLW levels (before 2006-11) stored their level set images at `images/`
     *
     * We haven't captured/saved these images to the server yet, so redirect to archive.org and hope they got a saved
     * copy
     *
     * @param string $fileName
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion1(string $fileName)
    {
        return redirect($this->getArchiveOrgFallbackUrl() . 'images/' . $fileName . '.jpg');
    }

    /**
     * These level sets use thumbnails from the rounds
     *
     * @param string $name
     * @param int $number
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showVersion2(string $name, int $number)
    {
        $fileName = $name . '/' . $number . '.jpg';

        $disk = Storage::disk('round-images');
        if ($disk->exists($fileName)) {
            return redirect($disk->url($fileName));
        }

        return redirect($this->getArchiveOrgFallbackUrl() . 'cache/' . $fileName);
    }

    /**
     * @return string
     */
    private function getArchiveOrgFallbackUrl(): string
    {
        return 'https://web.archive.org/web/20171205000449im_/http://www.ricochetInfinity.com/levels/';
    }
}
