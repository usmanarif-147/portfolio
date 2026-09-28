<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('indus_gas_expenses', function (Blueprint $table) {
            $table->foreignId('responsible_staff_id')->nullable()->constrained('indus_gas_staff')->nullOnDelete();
        });

        Schema::create('indus_gas_expense_category_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('indus_gas_expense_categories')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('indus_gas_staff')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['expense_category_id', 'staff_id'], 'ig_ec_staff_unique');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('indus_gas_expense_category_staff');
        Schema::table('indus_gas_expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('responsible_staff_id');
        });
    }
};
