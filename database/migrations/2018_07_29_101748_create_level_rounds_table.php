<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('level_set_id');
            $table->string('name')->index();
            $table->string('author')->index();
            $table->string('note1');
            $table->string('note2');
            $table->string('note3');
            $table->string('note4');
            $table->string('note5');
            $table->string('source');
            $table->string('image_file_name');
            $table->timestamps();

            $table->foreign('level_set_id')->references('id')->on('level_sets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_rounds');
    }
}
