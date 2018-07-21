<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $catalog = file_get_contents(storage_path('catalogx.bin'));

        return $catalog;
    }

    public function getCatalogHeader()
    {
        return <<<EOF
CCatalogWebResponse
{
  Success=1
  SessionID=343882
  Catalog URL=http://www.ricochetInfinity.com/gateway/catalog.php
  Download URL=http://www.ricochetInfinity.com/levels/download.php?File=downloads/raw/
  Submit URL=http://www.ricochetInfinity.com/levels/ri_submitform.php
  Image URL=http://www.ricochetInfinity.com/levels/
  Rate URL=http://www.ricochetInfinity.com/gateway/syncratings.php
  Can Test PreRelease Levels=
  Can Apply Star Tags=
  New Build Message=Go to www.RicochetInfinity.com to download an update for Ricochet Infinity
}
EOF;
    }
}
