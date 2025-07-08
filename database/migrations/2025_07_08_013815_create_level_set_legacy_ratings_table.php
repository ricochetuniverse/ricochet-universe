<?php

use App\LevelSet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('level_set_legacy_ratings', function (Blueprint $table) {
            $table->unsignedInteger('level_set_id')->index();
            $table->float('overall_rating', 4);
            $table->unsignedInteger('overall_weight');
            $table->float('fun_rating', 4);
            $table->unsignedInteger('fun_weight');
            $table->float('graphics_rating', 4);
            $table->unsignedInteger('graphics_weight');

            $table->foreign('level_set_id')->references('id')->on('level_sets');
        });

        DB::transaction(function () {
            LevelSet::where('overall_rating', '>', 0)
                ->orWhere('fun_rating', '>', 0)
                ->orWhere('graphics_rating', '>', 0)
                ->chunk(500, function ($levels) {
                    /** @var \Illuminate\Support\Collection<int, LevelSet> $levels */
                    foreach ($levels as $level) {
                        DB::table('level_set_legacy_ratings')->insert([
                            'level_set_id' => $level->id,
                            'overall_rating' => $level->overall_rating,
                            'overall_weight' => $level->overall_rating_count,
                            'fun_rating' => $level->fun_rating,
                            'fun_weight' => $level->fun_rating_count,
                            'graphics_rating' => $level->graphics_rating,
                            'graphics_weight' => $level->graphics_rating_count,
                        ]);
                    }
                });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_set_legacy_ratings');
    }
};
