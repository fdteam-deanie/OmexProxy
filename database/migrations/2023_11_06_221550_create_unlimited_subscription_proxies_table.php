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
        Schema::create('unlimited_subscription_proxies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unlimited_subscription_id')
                ->constrained('unlimited_subscriptions')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('proxy_id')
                ->constrained('proxies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['unlimited_subscription_id', 'proxy_id'], 'unlimited_subscription_proxies_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unlimited_subscription_proxies');
    }
};
