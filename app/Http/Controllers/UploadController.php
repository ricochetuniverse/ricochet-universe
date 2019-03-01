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
            'date_posted' => ['required', 'date_format:Y-m-d'],
        ]);

        $processor = new LevelSetUploadProcessor();
        $processor->setUrl($request->input('url'));
        $processor->setName($request->input('name'));
        $processor->setDatePosted(Carbon::createFromFormat('Y-m-d', $request->input('date_posted')));

        $levelSet = $processor->process();

        return redirect($levelSet->getPermalink());
    }
}
