<?php

namespace App\Services;

use App\Http\Transformers\RicochetCatalogTransformer;
use App\LevelSet;

class CatalogService
{
    public function getCatalog(bool $isSecure)
    {
        $response = $this->getCatalogHeader($isSecure);

        LevelSet::with('tagged')->chunk(100, function ($levels) use (&$response) {
            /** @var LevelSet[] $levels */
            foreach ($levels as $level) {
                $response .= RicochetCatalogTransformer::transform($level);
                $response .= "\r\n";
            }
        });

        return $response;
    }

    private function getCatalogHeader(bool $isSecure)
    {
        // $siteUrl = 'http://www.ricochetInfinity.com';
        $siteUrl = config('app.url');

        if (!$isSecure) {
            $siteUrl = preg_replace('/^https\:\/\//', 'http://', $siteUrl);
        }

        //$imageUrl = 'http://www.ricochetInfinity.com/levels/';
        $imageUrl = $siteUrl . '/levels/';

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
