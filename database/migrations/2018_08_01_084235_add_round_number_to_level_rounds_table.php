<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoundNumberToLevelRoundsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('level_rounds', function (Blueprint $table) {
            $table->unsignedSmallInteger('round_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_rounds', function (Blueprint $table) {
            $table->dropColumn('round_number');
        });
    }
}
