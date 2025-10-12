<?php

namespace App\Http\Controllers;

use App\Rules\ValidTimestamp;
use App\Services\LevelSetUploadProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        // url and name parameters are validated inside the processor
        $this->validate($request, [
            'url' => ['required', 'string', 'unique:App\\LevelSet,alternate_download_url'],
            'name' => ['required', 'string'],
            'timestamp' => ['required', 'integer', new ValidTimestamp],
        ], [
            'url.unique' => 'The level set URL is already submitted.',
        ], [
            'url' => 'level set URL',
        ]);

        $processor = new LevelSetUploadProcessor;
        $processor->url = $request->input('url');
        $processor->name = $request->input('name');
        $processor->datePosted = Carbon::createFromTimestampUTC($request->input('timestamp'));
        $processor->postToDiscord = true;

        $levelSet = $processor->process();

        return redirect($levelSet->getPermalink());
    }
}
