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
        Schema::rename('cart_items', 'cart_proxies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('cart_proxies', 'cart_items');
    }
};
