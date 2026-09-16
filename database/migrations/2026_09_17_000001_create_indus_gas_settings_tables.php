<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indus_gas_business_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Indus Gas');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('ntn', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('invoice_prefix', 20)->default('IG-');
            $table->text('invoice_footer')->nullable();
            $table->timestamps();
        });

        Schema::create('indus_gas_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('supply_type', 20);
            $table->string('contact_person')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('indus_gas_cylinder_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category', 30);
            $table->decimal('capacity_kg', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('indus_gas_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number', 30)->unique();
            $table->string('vehicle_type', 50)->default('Pickup');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('indus_gas_expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_expense_categories');
        Schema::dropIfExists('indus_gas_vehicles');
        Schema::dropIfExists('indus_gas_cylinder_types');
        Schema::dropIfExists('indus_gas_suppliers');
        Schema::dropIfExists('indus_gas_business_profiles');
    }
};
