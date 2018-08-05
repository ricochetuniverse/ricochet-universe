<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LevelSubmitController extends Controller
{
    public function submit(Request $request)
    {
        if (!app()->environment('local')) {
            throw new NotFoundHttpException;
        }

        $file = $request->file('file');

        // todo use $file->originalName, must sanitize
        // todo read the file, parse it

        $file->store('levels');

        return 'true';
    }
}
