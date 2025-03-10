<?php

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
        Schema::create('level_set_download_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('level_set_id')->index();
            $table->ipAddress('ip_address')->index();
            $table->timestamps();

            $table->foreign('level_set_id')->references('id')->on('level_sets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_set_download_logs');
    }
};
