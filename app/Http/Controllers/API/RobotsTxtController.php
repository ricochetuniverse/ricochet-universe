<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class RobotsTxtController extends Controller
{
    public function index()
    {
        return response(view('robots'))
            ->setCache(['public' => true, 'max_age' => 60 * 60])
            ->header('Content-Type', 'text/plain');
    }
}
