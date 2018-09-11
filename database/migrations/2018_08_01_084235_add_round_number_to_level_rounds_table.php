<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoundNumberToLevelRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('level_rounds', function (Blueprint $table) {
            $table->unsignedSmallInteger('round_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('level_rounds', function (Blueprint $table) {
            $table->dropColumn('round_number');
        });
    }
}
