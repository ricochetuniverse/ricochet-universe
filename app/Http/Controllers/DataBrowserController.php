<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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

    public function show(string $category, ?string $path = null)
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

        return $category.'<br>'.$path; // todo
    }

    public static function canAccess(): bool
    {
        return app()->environment('local') && ! empty(self::getCategories());
    }

    private static function getCategories(): array
    {
        return config('ricochet.data_browser_categories');
    }
}
