<?php

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
        Schema::create('level_set_user_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('level_set_id')->index();
            $table->string('player_name')->index();
            $table->unsignedTinyInteger('overall_grade')->nullable();
            $table->unsignedTinyInteger('fun_grade')->nullable();
            $table->unsignedTinyInteger('graphics_grade')->nullable();
            $table->timestamps();

            $table->foreign('level_set_id')->references('id')->on('level_sets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_set_user_ratings');
    }
};
