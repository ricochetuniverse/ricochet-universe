<?php

namespace App\Services;

class LevelSetDecompressService
{
    public function decompress($filename)
    {
        $fp = fopen($filename, 'rb');
        fseek($fp, 9);
        $compressed = fread($fp, filesize($filename));
        fclose($fp);

        return zlib_decode($compressed);
    }
}
