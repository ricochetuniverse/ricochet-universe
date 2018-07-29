<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index()
    {
        $response = Cache::remember('level_catalog', 10, function () {
            $catalogService = new CatalogService;
            return $catalogService->getCatalog();
        });

        return response($response)
            ->setCache(['public' => true, 'max_age' => 60 * 10])
            ->header('Content-Type', 'text/plain');
    }
}
