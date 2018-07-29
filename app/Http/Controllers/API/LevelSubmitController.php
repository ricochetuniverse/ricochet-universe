<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LevelSubmitController extends Controller
{
    public function submit(Request $request)
    {
        $file = $request->file('file');

        // todo use $file->originalName, must sanitize
        // todo read the file, parse it

        $file->store('levels');

        return 'true';
    }
}
