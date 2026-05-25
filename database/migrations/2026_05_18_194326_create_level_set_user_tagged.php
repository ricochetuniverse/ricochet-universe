<?php

declare(strict_types=1);

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
        Schema::create('level_set_user_tagged', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('level_set_id')->index();
            $table->unsignedBigInteger('tag_id')->index();
            $table->string('player_name')->index();
            $table->timestamps();

            $table->foreign('level_set_id')->references('id')->on('level_sets');
            $table->foreign('tag_id')->references('id')->on('level_set_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_set_user_tagged');
    }
};
