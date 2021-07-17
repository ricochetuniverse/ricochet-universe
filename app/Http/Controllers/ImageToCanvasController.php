<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageToCanvasController extends Controller
{
    public function index()
    {
        $available = app()->environment('local');

        if (!$available) {
            throw new NotFoundHttpException;
        }

        return view('image-to-canvas');
    }
}
