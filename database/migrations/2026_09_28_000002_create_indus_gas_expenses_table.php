<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('indus_gas_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('indus_gas_expense_categories')->restrictOnDelete();
            $table->foreignId('expense_type_id')->nullable()->constrained('indus_gas_expense_types')->nullOnDelete();
            $table->date('expense_date');
            $table->decimal('amount', 14, 2)->default(0);
            $table->unsignedInteger('cylinder_quantity')->nullable();
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->decimal('fuel_average_km_per_litre', 10, 2)->nullable();
            $table->decimal('fuel_rate_per_litre', 10, 2)->nullable();
            $table->string('driver', 100)->nullable();
            $table->json('payer_amounts')->nullable();
            $table->timestamps();
            $table->index('expense_date');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('indus_gas_expenses');
    }
};
