<?php

namespace App\Http\Controllers;

use App\Http\Transformers\RicochetCatalogTransformer;
use App\Level;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index()
    {
        $response = Cache::remember('level_catalog', 10, function () {
            $levels = Level::all();

            $response = $this->getCatalogHeader();
            $response .= $levels->map(function (Level $level) {
                return RicochetCatalogTransformer::transform($level);
            })->implode("\r\n");
            $response .= "\r\n";

            return $response;
        });

        return response($response)
            ->setCache(['public' => true, 'max_age' => 60 * 10])
            ->header('Content-Type', 'text/plain');
    }

    private function getCatalogHeader()
    {
        $imageUrl = 'http://www.ricochetInfinity.com/levels/';
        $imageUrl = 'http://web.archive.org/web/20171205000449im_/'.$imageUrl;

        $header = <<<EOF
CCatalogWebResponse
{
  Success=1
  SessionID=343882
  Catalog URL=http://www.ricochetInfinity.com/gateway/catalog.php
  Download URL=http://www.ricochetInfinity.com/levels/download.php?File=downloads/raw/
  Submit URL=http://www.ricochetInfinity.com/levels/ri_submitform.php
  Image URL=${imageUrl}
  Rate URL=http://www.ricochetInfinity.com/gateway/syncratings.php
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
