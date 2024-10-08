<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelSetsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('level_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('legacy_id');
            $table->string('name')->index();
            $table->unsignedInteger('rounds')->index();
            $table->string('author')->index();
            $table->boolean('featured')->default(false);
            $table->tinyInteger('game_version', false, true);
            $table->string('image_url');
            $table->float('rating', 5)->index()->default(0);
            $table->unsignedInteger('downloads')->index()->default(0);
            $table->text('description');
            $table->float('overall_rating', 4)->default(0);
            $table->unsignedInteger('overall_rating_count')->default(0);
            $table->float('fun_rating', 4)->default(0);
            $table->unsignedInteger('fun_rating_count')->default(0);
            $table->float('graphics_rating', 4)->default(0);
            $table->unsignedInteger('graphics_rating_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_sets');
    }
}
