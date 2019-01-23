<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('author');
            $table->text('description')->default('');
            $table->string('video_embed_source')->default('');
            $table->string('download_link')->default('');
            $table->string('trigger_codename');
            $table->timestamps();
        });

        Schema::create('level_set_mod', function (Blueprint $table) {
            $table->integer('level_set_id')->unsigned()->index();
            $table->integer('mod_id')->unsigned()->index();

            $table->foreign('level_set_id')->references('id')->on('level_sets');
            $table->foreign('mod_id')->references('id')->on('mods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_sets_mods');
        Schema::dropIfExists('mods');
    }
}
