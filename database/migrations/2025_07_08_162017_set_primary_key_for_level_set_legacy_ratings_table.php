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
        Schema::table('level_set_legacy_ratings', function (Blueprint $table) {
            $table->primary('level_set_id');
            $table->dropIndex(['level_set_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_set_legacy_ratings', function (Blueprint $table) {
            $table->dropPrimary(['level_set_id']);
            $table->index('level_set_id');
        });
    }
};
