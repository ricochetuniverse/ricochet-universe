<?php

namespace App\Services;

use App\LevelSet;

class CatalogService
{
    /**
     * @param bool $isSecure
     * @return string
     */
    public function getCatalog(bool $isSecure)
    {
        $response = $this->getCatalogHeader($isSecure);

        LevelSet::with('tagged')->chunk(100, function ($levels) use (&$response) {
            /** @var LevelSet[] $levels */
            foreach ($levels as $level) {
                $response .= $this->transformLevelSetToCatalogItem($level);
                $response .= "\r\n";
            }
        });

        return $response;
    }

    /**
     * @param bool $isSecure
     * @return string
     */
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

    /**
     * @param string $text
     * @return string
     */
    private function normalizeLineBreaks(string $text)
    {
        return str_replace(["\r\n", "\n"], "\r\n", $text);
    }

    /**
     * @param $level LevelSet
     * @return string
     */
    private function transformLevelSetToCatalogItem(LevelSet $level)
    {
        $data = [
            $level->legacy_id,
            str_replace(',', ';', $level->name),
            $level->rounds,
            $level->author,
            $level->created_at->format('Y-m-d'),
            (int)$level->featured,
            $level->game_version,
            0, // prerelease
            '', // required_build
            $level->image_url,
            $level->rating,
            $level->downloads,
            str_replace(',', ';', $level->description),
            $level->tags->pluck('name')->implode(';'),
            $level->overall_rating,
            $level->overall_rating_count,
            $level->fun_rating,
            $level->fun_rating_count,
            $level->graphics_rating,
            $level->graphics_rating_count,
            implode(';', []), // todo similar_levels
        ];

        return implode(',', $data);
    }
}
