<?php

declare(strict_types=1);

namespace App\Helpers;

use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;

class Str
{
    /**
     * https://stackoverflow.com/questions/22749182/laravel-escape-like-clause/42028380#42028380
     */
    public static function escapeLike(string $value, string $char = '\\'): string
    {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }

    // Idea from https://stackoverflow.com/a/17613163
    public static function readTextAsStream(string $data): \Generator
    {
        $path = 'data.txt';

        $adapter = new InMemoryFilesystemAdapter;
        $filesystem = new Filesystem($adapter);
        $filesystem->write($path, $data);

        $stream = $filesystem->readStream($path);

        $i = 0;
        while (($line = fgets($stream)) !== false) {
            yield $i => rtrim($line);

            $i += 1;
        }

        fclose($stream);
    }
}
