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
        Schema::table('countries', function (Blueprint $table) {
            $table->unsignedBigInteger('continent_id')->nullable()->change();
            $table->foreign('continent_id')->references('id')->on('continents')->onDelete('cascade');
        });

        Schema::table('zip', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->change();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->change();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->change();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropForeign(['continent_id']);
        });

        Schema::table('zip', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });
    }
};
