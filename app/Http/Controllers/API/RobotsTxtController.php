<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class RobotsTxtController extends Controller
{
    public function index()
    {
        return response(view('robots'))
            ->header('Content-Type', 'text/plain');
    }
}
