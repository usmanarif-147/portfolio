<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('visibility', 20)->default('public')->index()->after('status');
            $table->longText('content_html')->nullable()->after('content');
            $table->json('toc')->nullable()->after('content_html');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'content_html', 'toc']);
        });
    }
};
