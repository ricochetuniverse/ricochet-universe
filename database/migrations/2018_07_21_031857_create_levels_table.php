<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('legacy_id');
            $table->string('name')->index();
            $table->unsignedInteger('rounds')->index();
            $table->string('author')->index();
            $table->boolean('featured')->default(false);
            $table->string('game_version');
            $table->string('image_url');
            $table->float('rating')->index()->default(0);
            $table->unsignedInteger('downloads')->index()->default(0);
            $table->string('description');
            $table->float('overall_rating')->default(0);
            $table->unsignedInteger('overall_rating_count')->default(0);
            $table->float('fun_rating')->default(0);
            $table->unsignedInteger('fun_rating_count')->default(0);
            $table->float('graphics_rating')->default(0);
            $table->unsignedInteger('graphics_rating_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
