<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('projects', 'is_for_resume')) {
            DB::table('projects')
                ->where('is_for_resume', true)
                ->update(['is_featured' => true]);

            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('is_for_resume');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('projects', 'is_for_resume')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->boolean('is_for_resume')->default(false)->after('is_featured');
            });
        }
    }
};
