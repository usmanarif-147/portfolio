<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indus_gas_lpg_plant_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date')->unique();
            $table->decimal('rate_11_8_kg', 14, 4);
            $table->decimal('rate_per_kg', 14, 4);
            $table->decimal('rate_45_4_kg', 14, 4);
            $table->unsignedSmallInteger('filled_45_4_kg_cylinders')->default(0);
            $table->unsignedSmallInteger('filled_11_8_kg_cylinders')->default(0);
            $table->decimal('filling_charges', 14, 4)->default(0);
            $table->decimal('lpg_cost', 14, 4)->default(0);
            $table->decimal('total_cost', 14, 4)->default(0);
            $table->boolean('is_paid')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_lpg_plant_entries');
    }
};
