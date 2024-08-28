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
        Schema::table('settings', function (Blueprint $table) {
            \App\Models\Setting::set('trial_duration', 7, 'trial');
            \App\Models\Setting::set('trial_deposit_amount', 100, 'trial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            \App\Models\Setting::where('key', 'trial_duration')->delete();
            \App\Models\Setting::where('key', 'trial_deposit_amount')->delete();
        });
    }
};
