<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLevelSetsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->string('downloaded_file_name')->default('');
            $table->unsignedSmallInteger('round_to_get_image_from')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->dropColumn('downloaded_file_name');
            $table->dropColumn('round_to_get_image_from');
        });
    }
}
