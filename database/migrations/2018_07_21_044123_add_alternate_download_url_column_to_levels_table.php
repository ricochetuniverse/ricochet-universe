<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlternateDownloadUrlColumnToLevelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->string('alternate_download_url')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->dropColumn('alternate_download_url');
        });
    }
}
