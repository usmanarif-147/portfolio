<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('indus_gas_expense_category_staff', function (Blueprint $table) { $table->id(); $table->foreignId('expense_category_id')->constrained('indus_gas_expense_categories')->cascadeOnDelete(); $table->foreignId('staff_id')->constrained('indus_gas_staff')->cascadeOnDelete(); $table->timestamps(); $table->unique(['expense_category_id','staff_id']); }); } public function down(): void { Schema::dropIfExists('indus_gas_expense_category_staff'); } };
