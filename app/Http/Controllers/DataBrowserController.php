<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DataBrowserController extends Controller
{
    public function index() // : View
    {
        if (! self::canAccess()) {
            throw new NotFoundHttpException;
        }

        return 'hello';
        // return view('data-browser');
    }

    public static function canAccess(): bool
    {
        return app()->environment('local') && ! empty(config('ricochet.data_browser_categories'));
    }
}
