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
            $table->string('domain')->after('ip');
            $table->foreignId('state_id')->after('country_id')->nullable()->constrained('states');
            $table->foreignId('city_id')->after('state_id')->nullable()->constrained('cities');
            $table->foreignId('zip_id')->after('city_id')->nullable()->constrained('zip');
            $table->foreignId('isp_id')->after('zip_id')->nullable()->constrained('proxy_isp');
            $table->foreignId('org_id')->after('isp_id')->nullable()->constrained('proxy_orgs');
            $table->foreignId('type_id')->after('org_id')->nullable()->constrained('proxy_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_ips', function (Blueprint $table) {
            $table->dropColumn('domain');
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
            $table->dropForeign(['zip_id']);
            $table->dropColumn('zip_id');
            $table->dropForeign(['isp_id']);
            $table->dropColumn('isp_id');
            $table->dropForeign(['org_id']);
            $table->dropColumn('org_id');
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }
};
