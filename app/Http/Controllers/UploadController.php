<?php

namespace App\Http\Controllers;

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
        $this->validate($request, [
            'timestamp' => ['required', 'integer'],
        ]);

        $processor = new LevelSetUploadProcessor();
        $processor->setUrl($request->input('url'));
        $processor->setName($request->input('name'));
        $processor->setDatePosted(Carbon::createFromTimestamp($request->input('timestamp')));

        $levelSet = $processor->process();

        return redirect($levelSet->getPermalink());
    }
}
