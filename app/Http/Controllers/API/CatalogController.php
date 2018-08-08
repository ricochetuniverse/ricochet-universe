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
        $isSecure = $request->isSecure();

        $catalog = Cache::remember($this->getCacheKey($isSecure), $this->getCacheMinutes(), function () use ($isSecure) {
            $catalogService = new CatalogService;
            return $catalogService->getCatalog($isSecure);
        });

        $response = response($catalog)
            ->setCache(['public' => true, 'max_age' => 60 * $this->getCacheMinutes()])
            ->header('Content-Type', 'text/plain');

        if ($request->input('download')) {
            $response->header(
                'Content-Disposition',
                $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'catalogx.bin')
            );
        }

        return $response;
    }

    /**
     * @param bool $isSecure
     * @return string
     */
    private function getCacheKey(bool $isSecure): string
    {
        return $isSecure ? 'level_catalog' : 'level_catalog_http';
    }

    /**
     * @return int
     */
    private function getCacheMinutes(): int
    {
        if (app()->environment('production')) {
            return 10;
        }

        return 0;
    }
}
