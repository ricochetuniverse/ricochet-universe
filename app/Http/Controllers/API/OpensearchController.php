<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class OpensearchController extends Controller
{
    public function index()
    {
        return response(view('opensearch'))
            ->header('Content-Type', 'application/opensearchdescription+xml');
    }
}
