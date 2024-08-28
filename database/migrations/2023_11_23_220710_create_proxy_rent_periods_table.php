<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proxy_rent_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days')->default(1);
            $table->timestamps();
        });

        \App\Models\ProxyRentPeriod::create(['name' => '1 day', 'days' => 1]);
        \App\Models\ProxyRentPeriod::create(['name' => '3 days', 'days' => 3]);
        \App\Models\ProxyRentPeriod::create(['name' => '7 days', 'days' => 7]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxy_rent_periods');
    }
};
