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
        Schema::table('country_ips', function (Blueprint $table) {
            $table->integer('ping')->after('domain')->nullable();
            $table->integer('speed')->after('ping')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_ips', function (Blueprint $table) {
            $table->dropColumn('ping');
            $table->dropColumn('speed');
        });
    }
};
