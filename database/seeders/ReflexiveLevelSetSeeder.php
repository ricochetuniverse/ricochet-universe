<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\LevelSet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReflexiveLevelSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelSetId = DB::table('level_sets')->insertGetId([
            'legacy_id' => 1,
            'name' => 'Reflexive B Sides',
            'rounds' => 26,
            'author' => 'Reflexive',
            'game_version' => 2,
            'image_url' => 'images/ReflexiveBSides.jpg',
            'rating' => 4.10638,
            'downloads' => 40542,
            'description' => 'Bonus rounds form the original creators of Ricochet Lost Worlds.',
            'overall_rating' => 11.1383,
            'overall_rating_count' => 753,
            'fun_rating' => 11.4466,
            'fun_rating_count' => 652,
            'graphics_rating' => 11.2495,
            'graphics_rating_count' => 646,
            'created_at' => Carbon::parse('2004-04-22'),
            'alternate_download_url' => 'https://cdn.discordapp.com/attachments/389486692798693386/389486765984972800/Reflexive_B_Sides.RicochetI',
            'downloaded_file_name' => 'Reflexive B Sides.RicochetLW',
            'round_to_get_image_from' => 1,
        ]);

        DB::table('tagging_tags')->insert([
            'slug' => 'classic-style',
            'name' => 'Classic Style',
            'count' => 1,
        ]);

        DB::table('tagging_tagged')->insert([
            'taggable_id' => $levelSetId,
            'taggable_type' => LevelSet::class,
            'tag_name' => 'Classic Style',
            'tag_slug' => 'classic-style',
        ]);

        DB::table('level_set_legacy_ratings')->insert([
            'level_set_id' => $levelSetId,
            'overall_rating' => 11.1383,
            'overall_weight' => 753,
            'fun_rating' => 11.4466,
            'fun_weight' => 652,
            'graphics_rating' => 11.2495,
            'graphics_weight' => 646,
        ]);
    }
}
