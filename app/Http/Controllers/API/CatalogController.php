<?php

namespace App\Http\Controllers\API;

use App\Helpers\TextEncoderForGame;
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

        $catalogContent = Cache::remember(
            $this->getCacheKey($isSecure),
            now()->addMinutes($this->getCacheMinutes()),
            function () use ($isSecure) {
                return (new CatalogService)->getCatalog($isSecure);
            }
        );

        $response = response(TextEncoderForGame::toLegacyEncoding($catalogContent))
            ->setCache(['public' => true, 'max_age' => 60 * $this->getCacheMinutes()])
            ->setCharset(TextEncoderForGame::$legacyEncoding)
            ->header('Content-Type', 'text/plain');

        if ($request->input('download')) {
            $response->header(
                'Content-Disposition',
                $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'catalogx.bin')
            );
        }

        return $response;
    }

    private function getCacheKey(bool $isSecure): string
    {
        return $isSecure ? 'level_catalog' : 'level_catalog_http';
    }

    private function getCacheMinutes(): int
    {
        if (app()->environment('production')) {
            return 10;
        }

        return 0;
    }
}
