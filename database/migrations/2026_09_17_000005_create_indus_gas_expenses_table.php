<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indus_gas_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('indus_gas_expense_categories')->restrictOnDelete();
            $table->date('expense_date');
            $table->decimal('amount', 14, 2);
            $table->string('paid_by', 20);
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('reimbursement_status', 20)->default('not_required');
            $table->string('reimbursed_by', 20)->nullable();
            $table->timestamp('reimbursed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('indus_gas_cash_advances', function (Blueprint $table) {
            $table->id();
            $table->date('advance_date');
            $table->string('given_by', 20);
            $table->decimal('amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('indus_gas_partner_settlements', function (Blueprint $table) {
            $table->id();
            $table->date('settlement_date');
            $table->string('paid_by', 20);
            $table->string('received_by', 20);
            $table->decimal('amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_partner_settlements');
        Schema::dropIfExists('indus_gas_cash_advances');
        Schema::dropIfExists('indus_gas_expenses');
    }
};
