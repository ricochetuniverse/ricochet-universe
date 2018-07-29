<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $catalog = Cache::remember('level_catalog', 10, function () {
            $catalogService = new CatalogService;
            return $catalogService->getCatalog();
        });

        $response = response($catalog)
            ->setCache(['public' => true, 'max_age' => 60 * 10])
            ->header('Content-Type', 'text/plain');
        
        if ($request->input('download')) {
            $response->header(
                'Content-Disposition',
                $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'catalogx.bin')
            );
        }

        return $response;
    }
}
