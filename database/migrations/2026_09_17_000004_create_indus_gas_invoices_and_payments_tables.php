<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indus_gas_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->unique()->constrained('indus_gas_deliveries')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('indus_gas_customers')->restrictOnDelete();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->decimal('total_kg', 12, 2);
            $table->decimal('rate_per_kg', 12, 2);
            $table->decimal('total_amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('indus_gas_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('indus_gas_customers')->restrictOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 14, 2);
            $table->string('payment_method', 20);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('indus_gas_payment_invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('indus_gas_payments')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('indus_gas_invoices')->restrictOnDelete();
            $table->decimal('amount', 14, 2);
            $table->timestamps();
            $table->unique(['payment_id', 'invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_payment_invoice');
        Schema::dropIfExists('indus_gas_payments');
        Schema::dropIfExists('indus_gas_invoices');
    }
};
