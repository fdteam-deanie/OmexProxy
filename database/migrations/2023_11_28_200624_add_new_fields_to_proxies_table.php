<?php

use App\Models\Proxy;
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
            $table->string('name')->after('id');
            $table->integer('port')->after('ip');
            $table->foreignId('country_ip_id')->after('type_id')->nullable()->constrained('country_ips')->onDelete('cascade');
        });

        Proxy::all()->each(function ($proxy) {
            $proxy->name = uniqid("", true);
            $proxy->save();
        });

        Schema::table('proxies', function (Blueprint $table) {
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('port');
            $table->dropForeign(['country_ip_id']);
            $table->dropColumn('country_ip_id');
        });
    }
};
