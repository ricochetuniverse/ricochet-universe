<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageToCanvasController extends Controller
{
    public function index(): View
    {
        if (! self::canAccess()) {
            throw new NotFoundHttpException;
        }

        return view('image-to-canvas');
    }

    public static function canAccess(): bool
    {
        return app()->environment('local');
    }
}
