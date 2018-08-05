<?php

namespace App\Services;

use App\Http\Transformers\RicochetCatalogTransformer;
use App\LevelSet;

class CatalogService
{
    public function getCatalog()
    {
        $response = $this->getCatalogHeader();

        LevelSet::with('tagged')->chunk(100, function ($levels) use (&$response) {
            /** @var LevelSet[] $levels */
            foreach ($levels as $level) {
                $response .= RicochetCatalogTransformer::transform($level);
                $response .= "\r\n";
            }
        });

        return $response;
    }

    public static function getFallbackImageUrl()
    {
        return 'https://web.archive.org/web/20171205000449im_/http://www.ricochetInfinity.com/levels/';
    }

    private function getCatalogHeader()
    {
        // $siteUrl = 'http://www.ricochetInfinity.com';
        // $siteUrl = 'https://ricochet.ngyikp.com';
        $siteUrl = config('app.url');

        $imageUrl = static::getFallbackImageUrl();
        // $imageUrl = preg_replace('/^https\:\/\//', 'http://', $imageUrl);

        $header = <<<EOF
CCatalogWebResponse
{
  Success=1
  SessionID=343882
  Catalog URL=${siteUrl}/gateway/catalog.php
  Download URL=${siteUrl}/levels/download.php?File=downloads/raw/
  Submit URL=${siteUrl}/levels/ri_submitform.php
  Image URL=${imageUrl}
  Rate URL=${siteUrl}/gateway/syncratings.php
  Can Test PreRelease Levels=
  Can Apply Star Tags=
  New Build Message=Go to www.RicochetInfinity.com to download an update for Ricochet Infinity
}
id,name,rounds,author,date,featured,gameversion,prerelease,required_build,imageurl,rating,downloads,description,tags,overall_rating,overall_ratings,fun_rating,fun_ratings,graphics_rating,graphics_ratings,also_like

EOF;

        return $this->normalizeLineBreaks($header);
    }

    private function normalizeLineBreaks($text)
    {
        return str_replace(["\r\n", "\n"], "\r\n", $text);
    }
}
