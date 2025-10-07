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
        Schema::table('level_sets', function (Blueprint $table) {
            $table->boolean('prerelease')->default(false)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->dropColumn('prerelease');
        });
    }
};
