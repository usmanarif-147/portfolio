<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indus_gas_customers', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('title');
            $table->string('email')->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('location');
            $table->text('billing_address')->nullable()->after('city');
            $table->string('ntn', 50)->nullable()->after('type');
            $table->string('payment_term', 30)->default('cash')->after('ntn');
            $table->unsignedSmallInteger('payment_due_days')->nullable()->after('payment_term');
            $table->string('whatsapp_group_name')->nullable()->after('payment_due_days');
            $table->string('whatsapp_group_url', 2048)->nullable()->after('whatsapp_group_name');
            $table->text('notes')->nullable()->after('whatsapp_group_url');
            $table->boolean('is_active')->default(true)->after('notes');
        });

        Schema::create('indus_gas_customer_cylinder_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('indus_gas_customers')->cascadeOnDelete();
            $table->foreignId('cylinder_type_id')->constrained('indus_gas_cylinder_types')->restrictOnDelete();
            $table->unsignedSmallInteger('quantity')->default(0);
            $table->timestamps();

            $table->unique(['customer_id', 'cylinder_type_id'], 'indus_gas_customer_cylinder_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_customer_cylinder_allocations');

        Schema::table('indus_gas_customers', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person', 'email', 'city', 'billing_address', 'ntn', 'payment_term',
                'payment_due_days', 'whatsapp_group_name', 'whatsapp_group_url', 'notes', 'is_active',
            ]);
        });
    }
};
