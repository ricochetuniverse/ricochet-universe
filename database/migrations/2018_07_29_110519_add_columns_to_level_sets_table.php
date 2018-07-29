<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToLevelSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->string('downloaded_file_name')->default('');
            $table->unsignedSmallInteger('round_to_get_image_from')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('level_sets', function (Blueprint $table) {
            $table->dropColumn('downloaded_file_name');
            $table->dropColumn('round_to_get_image_from');
        });
    }
}
