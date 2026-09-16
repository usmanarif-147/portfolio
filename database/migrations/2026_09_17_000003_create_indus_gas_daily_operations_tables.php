<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indus_gas_refills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('indus_gas_suppliers')->restrictOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('indus_gas_vehicles')->nullOnDelete();
            $table->date('refill_date');
            $table->decimal('lpg_rate_per_kg', 12, 2);
            $table->decimal('filled_kg', 12, 2);
            $table->decimal('filling_charge_per_cylinder', 12, 2)->default(10);
            $table->string('payment_method', 20)->default('online');
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('indus_gas_refill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refill_id')->constrained('indus_gas_refills')->cascadeOnDelete();
            $table->foreignId('cylinder_type_id')->constrained('indus_gas_cylinder_types')->restrictOnDelete();
            $table->unsignedSmallInteger('cylinder_quantity');
            $table->timestamps();
            $table->unique(['refill_id', 'cylinder_type_id']);
        });

        Schema::create('indus_gas_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('indus_gas_customers')->restrictOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('indus_gas_vehicles')->nullOnDelete();
            $table->date('delivery_date');
            $table->decimal('sale_rate_per_kg', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('indus_gas_delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('indus_gas_deliveries')->cascadeOnDelete();
            $table->foreignId('cylinder_type_id')->constrained('indus_gas_cylinder_types')->restrictOnDelete();
            $table->unsignedSmallInteger('delivered_quantity');
            $table->unsignedSmallInteger('empty_collected_quantity')->default(0);
            $table->timestamps();
            $table->unique(['delivery_id', 'cylinder_type_id']);
        });

        Schema::create('indus_gas_stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cylinder_type_id')->constrained('indus_gas_cylinder_types')->restrictOnDelete();
            $table->date('adjustment_date');
            $table->string('stock_state', 10);
            $table->integer('quantity_change');
            $table->string('reason', 150);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_stock_adjustments');
        Schema::dropIfExists('indus_gas_delivery_items');
        Schema::dropIfExists('indus_gas_deliveries');
        Schema::dropIfExists('indus_gas_refill_items');
        Schema::dropIfExists('indus_gas_refills');
    }
};
