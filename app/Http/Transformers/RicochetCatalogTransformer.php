<?php

namespace App\Http\Transformers;

use App\Level;

class RicochetCatalogTransformer
{
    /**
     * @param $level Level
     * @return string
     */
    public static function transform(Level $level)
    {
        $data = [
            $level->legacy_id,
            $level->name,
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
            $level->description,
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
