<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rebuilds the tracking tables so uniqueness is keyed on a per-browser cookie
 * token (one row per unique visitor) instead of one row per raw event. Existing
 * rows are throwaway local test data, so the tables are dropped and recreated.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('resume_download_logs');

        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_token', 64)->unique();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('path', 255)->nullable();
            $table->unsignedInteger('visit_count')->default(1);
            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent()->index();
        });

        Schema::create('resume_download_logs', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_token', 64)->unique();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('template', 50)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('path', 255)->nullable();
            $table->unsignedInteger('download_count')->default(1);
            $table->timestamp('first_downloaded_at')->useCurrent();
            $table->timestamp('last_downloaded_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('resume_download_logs');

        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64)->index();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('path', 255)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });

        Schema::create('resume_download_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64)->index();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('template', 50)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('path', 255)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }
};
