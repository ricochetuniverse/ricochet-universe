<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviverController extends Controller
{
    const WINDOWS10 = 'windows10';
    const LEGACY_WINDOWS = 'windows8';
    const MACOS = 'macos';

    const GROUPS = [
        self::WINDOWS10 => 'Windows 10',
        self::LEGACY_WINDOWS => 'Windows 8.1 or below',
        self::MACOS => 'macOS',
    ];

    public function index()
    {
        return view('reviver');
    }

    public function show(string $os)
    {
        if (!in_array($os, array_keys(self::GROUPS), true)) {
            throw new NotFoundHttpException;
        }

        return view('reviver', ['os' => $os]);
    }
}
