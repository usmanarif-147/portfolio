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

        Schema::create('indus_gas_expense_reimbursements', function (Blueprint $table) {
            $table->id();
            $table->date('business_date')->index();
            $table->foreignId('paid_by_staff_id')->constrained('indus_gas_staff')->restrictOnDelete();
            $table->foreignId('received_by_staff_id')->constrained('indus_gas_staff')->restrictOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('payment_method', 30)->default('cash');
            $table->string('notes', 500)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['business_date', 'received_by_staff_id'], 'ig_reimbursements_day_recipient');
        });

        Schema::create('indus_gas_partner_settlements', function (Blueprint $table) {
            $table->id();
            $table->date('business_date')->index();
            $table->foreignId('paid_by_staff_id')->constrained('indus_gas_staff')->restrictOnDelete();
            $table->foreignId('received_by_staff_id')->constrained('indus_gas_staff')->restrictOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('payment_method', 30)->default('bank_transfer');
            $table->string('notes', 500)->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
            $table->index(['business_date', 'paid_by_staff_id'], 'ig_partner_settlements_day_payer');
        });

        Schema::create('indus_gas_expense_day_closings', function (Blueprint $table) {
            $table->id();
            $table->date('business_date')->unique();
            $table->foreignId('closed_by_staff_id')->nullable()->constrained('indus_gas_staff')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('indus_gas_expense_day_closings');
        Schema::dropIfExists('indus_gas_partner_settlements');
        Schema::dropIfExists('indus_gas_expense_reimbursements');
        Schema::dropIfExists('indus_gas_expense_category_staff');
        Schema::table('indus_gas_expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('responsible_staff_id');
        });
    }
};
