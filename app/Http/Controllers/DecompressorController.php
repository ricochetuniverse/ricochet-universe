<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;

class DecompressorController extends Controller
{
    public function index()
    {
        return view('decompressor', [
            'dotnetLoaderUrl' => $this->getDotnetLoaderUrl(),
        ]);
    }

    private function getDotnetLoaderUrl(): string
    {
        $file = file_get_contents(base_path('package.json'));
        if ($file === false) {
            return '';
        }

        $json = json_decode($file, true);
        if ($json === null) {
            return '';
        }

        $version = $json['dependencies']['@ricochetuniverse/nuvelocity-unpacker'];

        return URL::to('/build/nuvelocity-unpacker/'.$version.'/dotnet.js');
    }
}
