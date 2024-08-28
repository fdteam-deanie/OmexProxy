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
        Schema::table('proxies', function (Blueprint $table) {
            $table->unsignedBigInteger('continent_id')->nullable()->change();
            $table->unsignedBigInteger('country_id')->nullable()->change();
            $table->unsignedBigInteger('state_id')->nullable()->change();
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->unsignedBigInteger('zip_id')->nullable()->change();
            $table->unsignedBigInteger('org_id')->nullable()->change();
            $table->unsignedBigInteger('isp_id')->nullable()->change();
            $table->unsignedBigInteger('type_id')->nullable()->change();

            $table->foreign('continent_id')->references('id')->on('continents')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('zip_id')->references('id')->on('zip')->onDelete('cascade');
            $table->foreign('org_id')->references('id')->on('proxy_orgs')->onDelete('cascade');
            $table->foreign('isp_id')->references('id')->on('proxy_isp')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('proxy_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropForeign(['continent_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['zip_id']);
            $table->dropForeign(['org_id']);
            $table->dropForeign(['isp_id']);
            $table->dropForeign(['type_id']);
        });
    }
};
