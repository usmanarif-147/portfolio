<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('type', 20)->default('blog')->index()->after('status');
            $table->json('blocks')->nullable()->after('content_html');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn(['type', 'blocks']);
        });
    }
};
