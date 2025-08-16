<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviverController extends Controller
{
    const string WINDOWS10 = 'windows10';

    const string LEGACY_WINDOWS = 'windows8';

    const string MACOS = 'macos';

    const array GROUPS = [
        self::WINDOWS10 => 'Windows 11/10',
        self::LEGACY_WINDOWS => 'Windows 8.1 or below',
        self::MACOS => 'macOS',
    ];

    public function index()
    {
        return view('reviver.index');
    }

    public function show(string $os)
    {
        if (! array_key_exists($os, self::GROUPS)) {
            throw new NotFoundHttpException;
        }

        return view('reviver.index', ['os' => $os]);
    }

    public function generateData2DatFile()
    {
        $text = '[Channel]'."\n";
        $text .= 'Catalog URL='.preg_replace('/^https:\/\//', 'http://', action('API\\CatalogController@index'));

        return response($text, 200, [
            'Content-Disposition' => HeaderUtils::makeDisposition(HeaderUtils::DISPOSITION_ATTACHMENT, 'Data2.dat'),
            'Content-Type' => 'text/plain',
        ]);
    }
}
