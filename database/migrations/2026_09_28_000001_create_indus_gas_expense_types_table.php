<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('indus_gas_expense_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('indus_gas_expense_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['expense_category_id', 'name']);
            $table->unique(['expense_category_id', 'slug']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('indus_gas_expense_types');
    }
};
