<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DataBrowserController extends Controller
{
    public function index()
    {
        if (! self::canAccess()) {
            throw new NotFoundHttpException;
        }

        $categories = self::getCategories();
        if (count($categories) === 1) {
            return redirect()->action('DataBrowserController@show', [
                'category' => array_key_first($categories),
            ]);
        }

        return view('data-browser.index');
    }

    public function show(string $category, ?string $path = null): View
    {
        if (! self::canAccess()) {
            throw new NotFoundHttpException;
        }

        $categories = self::getCategories();
        if (! isset($categories[$category])) {
            throw new NotFoundHttpException;
        }

        $disk = Storage::disk('data-browser');
        if (! $disk->exists($category.'/'.$path)) {
            throw new NotFoundHttpException;
        }

        // todo move this json to better cache
        $allDirectoriesListing = self::getAllDirectoriesListing($disk, $category);

        return view('data-browser.show', [
            'category' => $category,
            'categoryName' => $categories[$category],
            'path' => $path,
            'storageUrl' => $disk->url($category.'/'),
            'allDirectoriesListing' => $allDirectoriesListing,
        ]);
    }

    public static function canAccess(): bool
    {
        return app()->environment('local') && ! empty(self::getCategories());
    }

    private static function getCategories(): array
    {
        return config('ricochet.data_browser_categories');
    }

    private static function getAllDirectoriesListing(Filesystem $disk, string $category): array
    {
        $allDirectoriesListing = [];

        $allDirectories = $disk->allDirectories($category);
        array_unshift($allDirectories, $category);
        sort($allDirectories);
        foreach ($allDirectories as $directory) {
            $listing = [
                'path' => $directory === $category ? '/' : str_replace($category.'/', '', $directory).'/',

                'directories' => array_map(static function ($item) use ($directory) {
                    return str_replace($directory.'/', '', $item);
                }, $disk->directories($directory)),

                'files' => array_map(static function ($item) use ($directory) {
                    return str_replace($directory.'/', '', $item);
                }, $disk->files($directory)),
            ];
            sort($listing['directories']);
            sort($listing['files']);

            $allDirectoriesListing[] = $listing;
        }

        return $allDirectoriesListing;
    }
}
