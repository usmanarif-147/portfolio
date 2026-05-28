<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds `is_for_resume` to projects and experiences so the live database can
 * receive the column without a `migrate:fresh` (important data is present).
 *
 * The same column is also declared in the original create migrations for clean
 * fresh installs; the `Schema::hasColumn` guards here keep this migration
 * idempotent so it cleanly no-ops on a fresh install where the column already
 * exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (! Schema::hasColumn('projects', 'is_for_resume')) {
                $table->boolean('is_for_resume')->default(false)->after('is_featured');
            }
        });

        Schema::table('experiences', function (Blueprint $table) {
            if (! Schema::hasColumn('experiences', 'is_for_resume')) {
                $table->boolean('is_for_resume')->default(false)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'is_for_resume')) {
                $table->dropColumn('is_for_resume');
            }
        });

        Schema::table('experiences', function (Blueprint $table) {
            if (Schema::hasColumn('experiences', 'is_for_resume')) {
                $table->dropColumn('is_for_resume');
            }
        });
    }
};
