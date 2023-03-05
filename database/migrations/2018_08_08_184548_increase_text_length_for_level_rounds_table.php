<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseTextLengthForLevelRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('level_rounds', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        // needs to be separate, I guess to let MariaDB flush changes?
        Schema::table('level_rounds', function (Blueprint $table) {
            $table->string('name', 1000)->change();
            $table->string('note1', 500)->change();
            $table->string('note2', 500)->change();
            $table->string('note3', 500)->change();
            $table->string('note4', 500)->change();
            $table->string('note5', 500)->change();

            // https://dev.mysql.com/doc/refman/8.0/en/innodb-restrictions.html
            //
            // The index key prefix length limit is 3072 bytes for InnoDB tables that use DYNAMIC or COMPRESSED row format.
            $table->index([DB::raw('name('.(3072 / 4).')')], 'level_rounds_name_index');
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
            $table->dropIndex(['name']);

            $table->string('name', 550)->index()->change();
            $table->string('note1', 255)->change();
            $table->string('note2', 255)->change();
            $table->string('note3', 255)->change();
            $table->string('note4', 255)->change();
            $table->string('note5', 255)->change();
        });
    }
}
