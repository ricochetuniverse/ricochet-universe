<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Foundation\MixFileNotFoundException;
use Illuminate\Foundation\MixManifestNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;

/**
 * Forked from https://github.com/laravel/framework/blob/12.x/src/Illuminate/Foundation/Mix.php to enable SRI
 *
 * @phpstan-type Entry array{path: string, integrity?: string}
 * @phpstan-type MixManifest array<string, Entry>
 */
abstract class MixManifestWithIntegrity
{
    /**
     * @var MixManifest
     */
    public static array $manifests = [];

    /**
     * @return Entry
     *
     * @throws MixFileNotFoundException
     * @throws MixManifestNotFoundException
     * @throws \JsonException
     */
    private static function getEntryFromManifest(string $path): array
    {
        if (! str_starts_with($path, '/')) {
            $path = "/{$path}";
        }

        $manifestPath = resource_path('mix-manifest.json');

        if (! isset(self::$manifests[$manifestPath])) {
            if (! is_file($manifestPath)) {
                throw new MixManifestNotFoundException("Mix manifest not found at: {$manifestPath}");
            }

            self::$manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true, flags: JSON_THROW_ON_ERROR);
        }

        $entry = self::$manifests[$manifestPath];

        if (! isset($entry[$path])) {
            $exception = new MixFileNotFoundException("Unable to locate file in Mix manifest: {$path}.");

            if (! App::hasDebugModeEnabled()) {
                report($exception);

                return ['path' => $path];
            }

            throw $exception;
        }

        return $entry[$path];
    }

    public static function getPath(string $path): HtmlString
    {
        $manifest = self::getEntryFromManifest($path);

        return new HtmlString($manifest['path']);
    }

    public static function getIntegrity(string $path): HtmlString
    {
        $manifest = self::getEntryFromManifest($path);

        return new HtmlString($manifest['integrity'] ?? null);
    }
}
