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
        Schema::table('level_set_user_ratings', function (Blueprint $table) {
            $table->unique(['level_set_id', 'player_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_set_user_ratings', function (Blueprint $table) {
            $table->dropUnique(['level_set_id', 'player_name']);
        });
    }
};
