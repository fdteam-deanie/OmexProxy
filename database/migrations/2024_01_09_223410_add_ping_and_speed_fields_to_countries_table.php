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
        Schema::table('countries', function (Blueprint $table) {
            $table->integer('min_ping')->default(50);
            $table->integer('max_ping')->default(150);
            $table->integer('min_speed')->default(70);
            $table->integer('max_speed')->default(130);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('min_ping');
            $table->dropColumn('max_ping');
            $table->dropColumn('min_speed');
            $table->dropColumn('max_speed');
        });
    }
};
