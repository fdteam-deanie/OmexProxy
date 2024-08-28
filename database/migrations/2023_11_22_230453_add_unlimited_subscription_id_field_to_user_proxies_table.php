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
        Schema::table('user_proxies', function (Blueprint $table) {
            $table->foreignId('unlimited_subscription_id')->after('proxy_id')->nullable()->constrained('unlimited_subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_proxies', function (Blueprint $table) {
            $table->dropForeign(['unlimited_subscription_id']);
            $table->dropColumn('unlimited_subscription_id');
        });
    }
};
