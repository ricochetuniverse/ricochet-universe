<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $catalog = Cache::remember('level_catalog', $this->getCacheMinutes(), function () {
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

    private function getCacheMinutes(): int
    {
        if (app()->environment('production')) {
            return 10;
        }

        return 0;
    }
}
