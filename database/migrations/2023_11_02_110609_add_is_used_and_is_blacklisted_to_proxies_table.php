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
        Schema::table('proxies', function (Blueprint $table) {
            $table->boolean('is_used')->default(false)->after('price');
            $table->boolean('is_blacklisted')->default(false)->after('is_used');
            $table->boolean('is_residential')->default(false)->after('is_blacklisted');
            $table->boolean('is_mobile')->default(false)->after('is_residential');
            $table->boolean('is_server')->default(false)->after('is_mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropColumn(['is_used', 'is_blacklisted', 'is_residential', 'is_mobile', 'is_server']);
        });
    }
};
